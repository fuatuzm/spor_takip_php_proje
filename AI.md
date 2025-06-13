## AI PROPMTLARI
veritabanımda iki tablo oluşturdum users ve workout şeklinde users ile girilern verileri listelicem workoutda ise yapılan spor çeşitlerini ama bu tabloyu oluşturmada zorluk çekiyorum yardımcı olur musun 
Elbette! Spor Takip Sistemi için users ve workout adlı iki tablo mantıklı bir yapı. Şimdi sana nasıl bir workout tablosu oluşturman gerektiğini detaylı şekilde açıklayayım.

💡 Genel Yapı:
users tablosu:
Kullanıcı bilgilerini içerir. Örnek alanlar:

sql
Kopyala
Düzenle
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL
);
🏋️‍♂️ workout tablosu:
Bu tablo, her bir kullanıcının yaptığı sporu, tarihi, süresi ve hangi tür spor olduğu gibi detayları içermelidir.

✅ Önerilen workout Tablosu Yapısı:
sql
Kopyala
Düzenle
CREATE TABLE workout (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,                          -- Bu spor kaydının hangi kullanıcıya ait olduğunu gösterir
    workout_type VARCHAR(50),            -- Spor türü (örnek: "kardiyo", "bacak", "göğüs" vs.)
    duration_minutes INT,                -- Sporun süresi (dakika cinsinden)
    workout_date DATE,                   -- Spor yapılan tarih
    notes TEXT,                          -- İsteğe bağlı açıklama
    FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE CASCADE
);
📌 Açıklamalar:
user_id: users tablosuyla bağlantı kurmak için.

workout_type: Spor çeşidini yazacaksın. (İstersen sabit liste de yaparız.)

duration_minutes: Kullanıcının ne kadar süre spor yaptığı.

workout_date: Hangi gün yapıldığını belirtmek için.

notes: İsteğe bağlı, açıklama kısmı.

🎯 Örnek veri girişi:
sql
Kopyala
Düzenle
INSERT INTO workout (user_id, workout_type, duration_minutes, workout_date, notes)
VALUES (1, 'kardiyo', 30, '2025-06-14', 'Koşu bandı, tempo yüksek');

login create list logout.php lerini oluşturmam için bana örnekler gösterir misin 

Tabii dostum! Sana adım adım çalışan bir PHP sistemin temel dosyalarını göstereyim. Bu sistem:

create.php → kullanıcı kayıt

login.php → giriş

list.php → veri listeleme (spor kayıtları)

logout.php → çıkış

Hepsi basit oturum mantığıyla çalışacak ve users tablosunu kullanacak. Spor kayıtları workout tablosundan çekilecek.

✅ 1. create.php – Kullanıcı Kayıt
php
Kopyala
Düzenle
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "spor_takip");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();

    echo "Kayıt başarılı. <a href='login.php'>Giriş yap</a>";
}
?>

<form method="POST">
    Kullanıcı Adı: <input type="text" name="username" required><br>
    E-posta: <input type="email" name="email" required><br>
    Şifre: <input type="password" name="password" required><br>
    <button type="submit">Kayıt Ol</button>
</form>
✅ 2. login.php – Kullanıcı Girişi
php
Kopyala
Düzenle
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "spor_takip");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["user_id"] = $id;
            header("Location: list.php");
            exit();
        }
    }
    echo "Hatalı giriş!";
}
?>

<form method="POST">
    Kullanıcı Adı: <input type="text" name="username" required><br>
    Şifre: <input type="password" name="password" required><br>
    <button type="submit">Giriş Yap</button>
</form>
✅ 3. list.php – Spor Verilerini Listele
php
Kopyala
Düzenle
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "spor_takip");
$user_id = $_SESSION["user_id"];

$result = $conn->query("SELECT * FROM workout WHERE user_id = $user_id ORDER BY workout_date DESC");

echo "<h2>Spor Kayıtlarınız</h2>";
echo "<a href='logout.php'>Çıkış Yap</a><br><br>";

while ($row = $result->fetch_assoc()) {
    echo "Tarih: " . $row["workout_date"] . " | ";
    echo "Tür: " . $row["workout_type"] . " | ";
    echo "Süre: " . $row["duration_minutes"] . " dk<br>";
}
?>
✅ 4. logout.php – Oturumu Sonlandır
php
Kopyala
Düzenle
<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
🔧 Notlar:
Tüm dosyaları aynı dizinde tut.

spor_takip adında bir veritabanı oluştur.

users ve workout tabloları az önce verdiğimiz gibi olmalı.

Güvenlik için prepared statements kullanıldı.

Arayüz sade ama istersen Bootstrap ile şıklaştırabiliriz.






