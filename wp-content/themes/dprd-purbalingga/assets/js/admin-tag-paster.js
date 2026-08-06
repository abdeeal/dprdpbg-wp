/**
 * Auto Split & Paste Multi-line Tags in Gutenberg & Classic Editor
 * DPRD Purbalingga Theme
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Intercept paste event on Tag input fields
    document.addEventListener('paste', function(e) {
        var target = e.target;
        if (!target) return;

        // Check if target is Gutenberg FormTokenField input OR Classic Editor tag input
        var isTagInput = target.matches('.components-form-token-field__input') ||
                         target.matches('#new-tag-post_tag') ||
                         target.closest('.editor-post-taxonomies__flat-term-selector') ||
                         target.closest('.components-form-token-field') ||
                         target.closest('[class*="taxonomy-tags"]');

        if (!isTagInput) return;

        var clipboardData = e.clipboardData || window.clipboardData;
        if (!clipboardData) return;

        var pastedText = clipboardData.getData('text');
        if (!pastedText) return;

        // Split by newlines, bullets (•), dashes, asterisks, or semicolons
        var rawItems = pastedText.split(/[\r\n;•]+/);
        var cleanTags = [];

        rawItems.forEach(function(item) {
            // Strip leading bullet symbols (•), numbers, dashes, asterisks, extra spaces
            var cleaned = item.replace(/^[\s\d\.\-\*•]+/, '').trim();
            if (cleaned.length > 0) {
                cleanTags.push(cleaned);
            }
        });

        // If there's only 1 single line without newlines or bullets, let standard browser paste work
        if (cleanTags.length <= 1 && !pastedText.includes('\n') && !pastedText.includes('•')) {
            return;
        }

        // Prevent standard paste which would dump all lines into one single tag
        e.preventDefault();
        e.stopPropagation();

        processPastedTags(cleanTags, target);
    }, true);

    /**
     * Process tags array into Gutenberg Store or Input Element
     */
    async function processPastedTags(tagsArray, inputElem) {
        if (typeof wp !== 'undefined' && wp.data && wp.data.dispatch && wp.data.select && wp.apiFetch) {
            try {
                // Get current post_tag taxonomy term IDs
                var currentTags = wp.data.select('core/editor').getEditedPostAttribute('tags') || [];
                var updatedTags = Array.from(currentTags);

                for (var i = 0; i < tagsArray.length; i++) {
                    var tagName = tagsArray[i];
                    if (!tagName) continue;

                    // Query existing tag or create new tag via WP REST API
                    var searchRes = await wp.apiFetch({
                        path: '/wp/v2/tags?search=' + encodeURIComponent(tagName)
                    });

                    var existingTag = null;
                    if (Array.isArray(searchRes) && searchRes.length > 0) {
                        existingTag = searchRes.find(function(t) {
                            return t.name.toLowerCase() === tagName.toLowerCase();
                        });
                    }

                    if (existingTag) {
                        if (!updatedTags.includes(existingTag.id)) {
                            updatedTags.push(existingTag.id);
                        }
                    } else {
                        // Create new tag via REST API
                        try {
                            var newTag = await wp.apiFetch({
                                path: '/wp/v2/tags',
                                method: 'POST',
                                data: { name: tagName }
                            });
                            if (newTag && newTag.id && !updatedTags.includes(newTag.id)) {
                                updatedTags.push(newTag.id);
                            }
                        } catch (errCreate) {
                            console.warn('Could not create tag:', tagName, errCreate);
                        }
                    }
                }

                // Update Gutenberg editor state
                wp.data.dispatch('core/editor').editPost({ tags: updatedTags });

                // Clear input element
                if (inputElem) {
                    inputElem.value = '';
                    inputElem.focus();
                }
                return;
            } catch (err) {
                console.error('Gutenberg tag update error:', err);
            }
        }

        // Fallback for Classic Editor or standard input
        if (inputElem) {
            inputElem.value = tagsArray.join(', ');
            var addBtn = inputElem.parentNode ? inputElem.parentNode.querySelector('.tagadd') : null;
            if (addBtn) {
                addBtn.click();
            } else {
                var event = new Event('input', { bubbles: true });
                inputElem.dispatchEvent(event);
            }
        }
    }
});
