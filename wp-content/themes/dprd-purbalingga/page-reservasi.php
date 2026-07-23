<?php
/**
 * Template Name: Reservasi Kunjungan
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<div class="bg-main min-h-screen pt-10 pb-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <!-- Header Reservasi -->
        <div class="mb-10">
            <h1 class="font-display text-3xl md:text-[32px] text-ink mb-3">Reservasi Kunjungan Kerja</h1>
            <p class="font-sans text-body-secondary text-[14px] leading-relaxed max-w-2xl">
                Silakan lengkapi formulir di bawah ini untuk mengajukan permohonan kunjungan dinas atau<br class="hidden sm:block"/> studi banding ke DPRD Kabupaten Purbalingga.
            </p>
        </div>

        <!-- Notifikasi -->
        <?php if (isset($_GET['status']) && $_GET['status'] === 'sukses') : ?>
            <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-button flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-600 shrink-0 mt-0.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <div>
                    <h3 class="font-sans font-bold text-green-800 text-[14px] mb-1">Reservasi Berhasil Dikirim</h3>
                    <p class="font-sans text-[13px] text-green-700 leading-relaxed">Permohonan kunjungan Anda telah kami terima dan akan segera diproses. Kami akan menghubungi Anda melalui WhatsApp atau Email untuk konfirmasi lebih lanjut.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Card Container -->
        <div class="bg-white border border-line rounded-card p-6 md:p-10 shadow-sm">
            <form id="dprd-reservasi-form" method="post" enctype="multipart/form-data" class="space-y-12">
                <?php wp_nonce_field('dprd_reservasi_nonce', 'dprd_reservasi_security'); ?>
                <input type="hidden" name="action" value="dprd_submit_reservasi">
                
                <!-- Section 1: Informasi Instansi -->
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-8 h-8 rounded-[4px] bg-primary-light text-primary flex items-center justify-center font-bold text-[14px]">1</div>
                        <h2 class="font-sans font-bold text-ink text-[16px]">Informasi Instansi</h2>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_email">
                                Email Instansi/Narahubung <span class="text-primary">*</span>
                            </label>
                            <input type="email" id="res_email" name="res_email" required placeholder="nama@instansi.go.id" class="w-full border border-line rounded-button px-4 py-2.5 font-sans text-[14px] text-ink placeholder:text-body-secondary/60 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50"/>
                        </div>
                        <div>
                            <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_nama_instansi">
                                Nama Instansi <span class="text-primary">*</span>
                            </label>
                            <input type="text" id="res_nama_instansi" name="res_nama_instansi" required placeholder="Contoh: SMA Negeri 1 Purbalingga" class="w-full border border-line rounded-button px-4 py-2.5 font-sans text-[14px] text-ink placeholder:text-body-secondary/60 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50"/>
                        </div>
                        <div>
                            <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_alamat_instansi">
                                Alamat Instansi <span class="text-primary">*</span>
                            </label>
                            <textarea id="res_alamat_instansi" name="res_alamat_instansi" rows="3" required placeholder="Masukkan alamat lengkap instansi..." class="w-full border border-line rounded-button px-4 py-2.5 font-sans text-[14px] text-ink placeholder:text-body-secondary/60 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50 resize-none overflow-hidden"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Detail Kunjungan -->
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-8 h-8 rounded-[4px] bg-primary-light text-primary flex items-center justify-center font-bold text-[14px]">2</div>
                        <h2 class="font-sans font-bold text-ink text-[16px]">Detail Kunjungan</h2>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_tanggal">
                                Rencana Tanggal Kunjungan <span class="text-primary">*</span>
                            </label>
                            <input type="date" id="res_tanggal" name="res_tanggal" min="<?php echo date('Y-m-d'); ?>" required class="w-full border border-line rounded-button px-4 py-2.5 font-sans text-[14px] text-body-secondary focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50 uppercase"/>
                            <p class="font-sans text-[11px] text-body-secondary mt-1.5 italic">Kunjungan kerja hanya dilayani Senin - Jumat</p>
                        </div>
                        <div>
                            <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_tema">
                                Tema / Materi Kunjungan <span class="text-primary">*</span>
                            </label>
                            <textarea id="res_tema" name="res_tema" rows="3" required placeholder="Sebutkan maksud dan tujuan kunjungan secara spesifik..." class="w-full border border-line rounded-button px-4 py-2.5 font-sans text-[14px] text-ink placeholder:text-body-secondary/60 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50 resize-none overflow-hidden"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Rombongan & Dokumen -->
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-8 h-8 rounded-[4px] bg-primary-light text-primary flex items-center justify-center font-bold text-[14px]">3</div>
                        <h2 class="font-sans font-bold text-ink text-[16px]">Rombongan &amp; Dokumen</h2>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_jabatan_pimpinan">
                                    Jabatan Pimpinan <span class="text-primary">*</span>
                                </label>
                                <input type="text" id="res_jabatan_pimpinan" name="res_jabatan_pimpinan" required placeholder="Contoh: Kepala Sekolah / Ketua Komisi" class="w-full border border-line rounded-button px-4 py-2.5 font-sans text-[14px] text-ink placeholder:text-body-secondary/60 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50"/>
                            </div>
                            <div>
                                <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_nama_pimpinan">
                                    Nama Pimpinan Rombongan <span class="text-primary">*</span>
                                </label>
                                <input type="text" id="res_nama_pimpinan" name="res_nama_pimpinan" required placeholder="Nama Lengkap &amp; Gelar" class="w-full border border-line rounded-button px-4 py-2.5 font-sans text-[14px] text-ink placeholder:text-body-secondary/60 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block font-sans text-[12px] text-ink font-medium mb-1.5">
                                    Jumlah Peserta <span class="text-primary">*</span>
                                </label>
                                <div class="flex items-center border border-line rounded-button overflow-hidden h-[42px]">
                                    <button type="button" id="dprd-jumlah-minus" class="px-4 h-full flex items-center justify-center text-ink hover:bg-surface transition-colors border-r border-line outline-none focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus" aria-hidden="true"><path d="M5 12h14"></path></svg>
                                    </button>
                                    <input type="number" id="res_jumlah_peserta" name="res_jumlah_peserta" min="1" value="1" required class="flex-1 text-center font-mono text-[14px] text-ink outline-none h-full bg-transparent appearance-none [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"/>
                                    <button type="button" id="dprd-jumlah-plus" class="px-4 h-full flex items-center justify-center text-ink hover:bg-surface transition-colors border-l border-line outline-none focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus" aria-hidden="true"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block font-sans text-[12px] text-ink font-medium mb-1.5" for="res_wa">
                                    Narahubung (WhatsApp) <span class="text-primary">*</span>
                                </label>
                                <div class="flex items-center border border-line rounded-button overflow-hidden focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 h-[42px] bg-white">
                                    <span class="bg-surface px-3 h-full flex items-center justify-center font-mono text-[13px] text-ink font-bold border-r border-line shrink-0 select-none">
                                        🇮🇩 +62
                                    </span>
                                    <input type="tel" id="res_wa" name="res_wa" required placeholder="81234567890" maxlength="13" class="w-full px-3 h-full font-sans text-[14px] text-ink placeholder:text-body-secondary/60 outline-none bg-transparent"/>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-sans text-[12px] text-ink font-medium mb-1.5">
                                Upload Surat Permohonan <span class="text-primary">*</span>
                            </label>
                            <div id="dprd-upload-box" class="bg-primary-light/50 border border-dashed border-primary/30 rounded-button p-8 flex flex-col items-center justify-center cursor-pointer hover:bg-primary-light transition-colors group relative">
                                <input type="file" id="res_file_surat" name="res_file_surat" accept=".pdf" class="hidden" required/>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-up text-primary mb-3 group-hover:-translate-y-1 transition-transform" aria-hidden="true"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"></path><path d="M14 2v5a1 1 0 0 0 1 1h5"></path><path d="M12 12v6"></path><path d="m15 15-3-3-3 3"></path></svg>
                                <span id="dprd-upload-text" class="font-sans text-[13px] text-ink mb-1.5 text-center px-4">Klik atau seret file PDF ke sini</span>
                                <span id="dprd-upload-subtext" class="font-mono text-[10px] text-body-secondary uppercase tracking-widest text-center">Maksimal 5MB (PDF)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="dprd-submit-btn" class="w-full bg-primary hover:bg-primary/90 text-white font-sans font-medium text-[15px] py-3.5 rounded-button transition-all duration-300 shadow-sm cursor-pointer border-0 outline-none flex items-center justify-center gap-2">
                    <span>Ajukan Reservasi</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Popup Alert Custom (Premium Design System 1:1 Vercel) -->
<div id="dprd-modal-overlay" class="fixed inset-0 bg-ink/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
    <div id="dprd-modal-card" class="bg-white border border-line rounded-card max-w-md w-full p-6 md:p-8 shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] flex flex-col items-center text-center">
        <!-- Icon Container -->
        <div id="dprd-modal-icon-bg" class="w-16 h-16 rounded-full flex items-center justify-center mb-5 transition-transform duration-500 scale-0">
            <div id="dprd-modal-icon"></div>
        </div>

        <!-- Title & Message -->
        <h3 id="dprd-modal-title" class="font-display font-bold text-xl md:text-2xl text-ink mb-2"></h3>
        <p id="dprd-modal-message" class="font-sans text-[14px] text-body-secondary leading-relaxed mb-6"></p>

        <!-- Action Button -->
        <button id="dprd-modal-close-btn" type="button" class="w-full bg-primary hover:bg-primary/90 text-white font-sans font-medium text-[14px] py-3 rounded-button transition-colors cursor-pointer border-0 outline-none">
            Tutup
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form          = document.getElementById('dprd-reservasi-form');
    const submitBtn     = document.getElementById('dprd-submit-btn');
    const uploadBox     = document.getElementById('dprd-upload-box');
    const fileInput     = document.getElementById('res_file_surat');
    const uploadText    = document.getElementById('dprd-upload-text');
    const btnMinus      = document.getElementById('dprd-jumlah-minus');
    const btnPlus       = document.getElementById('dprd-jumlah-plus');
    const inputCount    = document.getElementById('res_jumlah_peserta');

    // Modal UI Elements
    const modalOverlay  = document.getElementById('dprd-modal-overlay');
    const modalCard     = document.getElementById('dprd-modal-card');
    const modalIconBg   = document.getElementById('dprd-modal-icon-bg');
    const modalIcon     = document.getElementById('dprd-modal-icon');
    const modalTitle    = document.getElementById('dprd-modal-title');
    const modalMessage  = document.getElementById('dprd-modal-message');
    const modalCloseBtn = document.getElementById('dprd-modal-close-btn');

    function openModal(isSuccess, title, message) {
        modalTitle.textContent   = title;
        modalMessage.textContent = message;

        if (isSuccess) {
            modalIconBg.className = 'w-16 h-16 rounded-full flex items-center justify-center mb-5 bg-emerald-50 text-emerald-600 transition-transform duration-500 scale-100';
            modalIcon.innerHTML   = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle-2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>`;
            modalCloseBtn.className = 'w-full bg-emerald-600 hover:bg-emerald-700 text-white font-sans font-medium text-[14px] py-3 rounded-button transition-colors cursor-pointer border-0 outline-none';
        } else {
            modalIconBg.className = 'w-16 h-16 rounded-full flex items-center justify-center mb-5 bg-rose-50 text-rose-600 transition-transform duration-500 scale-100';
            modalIcon.innerHTML   = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`;
            modalCloseBtn.className = 'w-full bg-primary hover:bg-primary/90 text-white font-sans font-medium text-[14px] py-3 rounded-button transition-colors cursor-pointer border-0 outline-none';
        }

        modalOverlay.classList.remove('opacity-0', 'pointer-events-none');
        modalOverlay.classList.add('opacity-100');
        
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeModal() {
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modalOverlay.classList.remove('opacity-100');
            modalOverlay.classList.add('opacity-0', 'pointer-events-none');
        }, 200);
    }

    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) closeModal();
        });
    }

    // WhatsApp Input Numeric & Prefix Formatter
    const waInput = document.getElementById('res_wa');
    if (waInput) {
        waInput.addEventListener('input', function() {
            let val = this.value.replace(/[^0-9]/g, '');
            if (val.startsWith('0')) {
                val = val.substring(1);
            } else if (val.startsWith('62')) {
                val = val.substring(2);
            }
            this.value = val;
        });
    }

    // Number Stepper Handler
    if (btnMinus && btnPlus && inputCount) {
        btnMinus.addEventListener('click', () => {
            let val = parseInt(inputCount.value) || 1;
            if (val > 1) inputCount.value = val - 1;
        });
        btnPlus.addEventListener('click', () => {
            let val = parseInt(inputCount.value) || 1;
            inputCount.value = val + 1;
        });
    }

    // Drag and Drop File Handler
    if (uploadBox && fileInput) {
        uploadBox.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                uploadText.innerHTML = '<strong class="text-primary">📄 File Terpilih: ' + fileInput.files[0].name + '</strong>';
            }
        });
    }

    // AJAX Submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const nonceVal = document.getElementById('dprd_reservasi_security')?.value || '';
            formData.append('dprd_reservasi_security', nonceVal);
            formData.append('security', nonceVal);

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mengirim Permohonan...</span>
            `;

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Ajukan Reservasi</span>';

                if (data.success) {
                    openModal(true, 'Permohonan Berhasil Dikirim!', data.data.message);
                    form.reset();
                    if (uploadText) uploadText.innerHTML = 'Klik atau seret file PDF ke sini';
                } else {
                    openModal(false, 'Gagal Mengirim Permohonan', data.data?.message || 'Terjadi kesalahan pada sistem.');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Ajukan Reservasi</span>';
                openModal(false, 'Koneksi Terputus', 'Terjadi kesalahan jaringan. Silakan periksa koneksi internet Anda.');
            });
        });
    }
});
</script>

<?php
get_footer();
