1. Pastikan bahwa Anda memiliki server web yang diinstal di komputer Anda, seperti Apache atau Nginx. Jika belum, Anda dapat menginstal XAMPP atau WAMP sebagai 
paket lengkap yang mencakup Apache, PHP, MySQL, dan alat pendukung web lainnya. (lebih baik menggunakan xampp)
2. Unduh Project sistem login ini dari email dan ekstrak file ZIP-nya.
3. Pindahkan direktori Project sistem login ini(folder yang telah di ekstrak) ke direktori root server web Anda. Jika Anda menggunakan XAMPP, direktori root default biasanya adalah htdocs.
4. Buka aplikasi xampp, hidupkan apache dan mysql dan tekan admin mysql untuk masuk ke database phpmyadmin
5. setelah masuk diphpmyadmin, buat database baru dengan nama yang sama dengan database di folder project sistem login ini. (nama database : sistem)
6. setelah membuat database dengan nama sistem, selanjutnya anda dapat memilih import untuk memasukan database yang ada di folder project tersebut dengan nama file : sistem.sql
7. setelah anda import, selanjutnya anda dapat menjalankan aplikasinya di google dengan url http://localhost/sistem/