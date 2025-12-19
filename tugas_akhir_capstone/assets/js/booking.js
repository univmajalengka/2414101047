(function(){
    const form = document.querySelector('form.form-container');
    const serviceCheckboxes = Array.from(document.querySelectorAll('input[name="pelayanan[]"]'));
    const pesertaEl = document.getElementById('jumlah_peserta');
    const hariEl = document.getElementById('waktu_pelaksanaan_hari');
    const hargaPaketHidden = document.getElementById('harga_paket');
    const hargaPaketDisplay = document.getElementById('harga_paket_display');
    const jumlahTagihanHidden = document.getElementById('jumlah_tagihan');
    const jumlahTagihanDisplay = document.getElementById('jumlah_tagihan_display');
    const clientErrorsEl = document.getElementById('clientErrors');

    function parseNumber(n){ return Number(n) || 0; }

    function formatRupiah(amount){
        if (!isFinite(amount)) amount = 0;
        amount = Math.round(amount);
        return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function calc(){
        let harga = 0;
        serviceCheckboxes.forEach(cb => { if(cb.checked) harga += parseNumber(cb.dataset.price); });
        const peserta = Math.max(1, parseInt(pesertaEl.value || 1));
        const hari = Math.max(1, parseInt(hariEl.value || 1));
        const total = harga * peserta * hari;

        if (hargaPaketHidden) hargaPaketHidden.value = harga;
        if (hargaPaketDisplay) hargaPaketDisplay.value = formatRupiah(harga);
        if (jumlahTagihanHidden) jumlahTagihanHidden.value = total.toFixed(2);
        if (jumlahTagihanDisplay) jumlahTagihanDisplay.value = formatRupiah(total);
    }

    function isValidPhoneNumber(p){
        if (!p) return false;
        const digits = p.replace(/\D/g, '');
        return digits.length >= 7 && digits.length <= 15;
    }

    function showClientErrors(errs){
        if (!clientErrorsEl) return;
        if (!errs || errs.length === 0) { clientErrorsEl.style.display = 'none'; clientErrorsEl.innerHTML = ''; return; }
        clientErrorsEl.style.display = 'block';
        clientErrorsEl.innerHTML = errs.map(e => '<div>'+e+'</div>').join('');
    }

    function validate(){
        const errs = [];
        const nama = (document.querySelector('input[name="nama_pemesan"]') || {}).value || '';
        const hp = (document.querySelector('input[name="nomor_hp"]') || {}).value || '';
        if (nama.trim() === '') errs.push('Nama Pemesan harus diisi.');
        if (!isValidPhoneNumber(hp)) errs.push('Nomor HP tidak valid (minimal 7 digit).');
        if (serviceCheckboxes.every(cb => !cb.checked)) errs.push('Pilih minimal satu pelayanan.');
        if (parseInt(pesertaEl.value || 0) < 1) errs.push('Jumlah peserta harus lebih dari 0.');
        if (parseInt(hariEl.value || 0) < 1) errs.push('Waktu perjalanan (hari) harus minimal 1.');
        return errs;
    }

    // events
    serviceCheckboxes.forEach(cb => cb.addEventListener('change', calc));
    pesertaEl && pesertaEl.addEventListener('input', calc);
    hariEl && hariEl.addEventListener('input', calc);

    if (form){
        form.addEventListener('submit', function(e){
            const errs = validate();
            if (errs.length){
                e.preventDefault();
                showClientErrors(errs);
                clientErrorsEl && clientErrorsEl.scrollIntoView({behavior: 'smooth', block: 'center'});
                return false;
            }
            showClientErrors([]);
        });
    }

    // initial calc
    calc();
})();
