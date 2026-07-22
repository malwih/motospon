{{-- ============================================================ --}}
{{-- FOOTER UTAMA --}}
{{-- ============================================================ --}}
{{--
    Komponen footer untuk tampilan utama
    - Posisi di bagian bawah halaman
    - Background abu-abu muda
    - Lebar penuh dengan padding responsif
--}}
<footer class="mx-auto bg-gray-100 w-full max-w-container px-4 sm:px-6 lg:px-8">
    {{-- ============================================================ --}}
    {{-- KONTAINER FOOTER --}}
    {{-- ============================================================ --}}
    {{--
        Container untuk konten footer
        - Border atas dengan warna yang sesuai tema
        - Padding vertikal untuk spasi
    --}}
    <div class="border-t border-slate-900/20 py-10">
            {{-- ============================================================ --}}
            {{-- LOGO FOOTER --}}
            {{-- ============================================================ --}}
            {{--
                Logo di bagian footer
                - Posisi tengah (mx-auto)
                - Ukuran yang sesuai
                - Mengarah ke halaman utama saat diklik
            --}}
            <a href="/">
                <img class="mx-auto h-10 w-auto text-slate-900" src="../storage/img/logo.png" height="250" width="300" alt="Motospon Logo" />
            </a>
        {{-- ============================================================ --}}
        {{-- HAK CIPTA --}}
        {{-- ============================================================ --}}
        {{--
            Teks hak cipta
            - Posisi tengah
            - Ukuran teks kecil
            - Warna abu-abu untuk kontras yang tepat
        --}}
        <p class="mt-5 text-center text-sm leading-6 text-slate-500">© {{ date('Y') }} Motospon. All rights reserved.</p>
    </div>
</footer>