## Kurulum

1. Repoyu klonlayın:
   ```
   git clone https://github.com/Mehmet-Alemdar/news-app.git
   ```
   ```
   cd news-app
   ```

2. Bağımlılıkları yükleyin:
   ```
   composer install
   ```

3. .env dosyasını oluşturun ve veritabanı bilgilerinizi girin:
   ```
   cp .env.example .env
   # .env dosyasını düzenleyin
   ```

4. Uygulama anahtarını oluşturun:
   ```
   php artisan key:generate
   ```

5. Migration işlemlerini başlatın:
   ```
   php artisan migrate
   ```

6. **Dikkat:** Seeder otomatik olarak 250.000 haber kaydı oluşturacaktır. Bu işlem sisteminize göre uzun sürebilir.
   
   Seed işlemini başlatmak için:
   ```
   php artisan db:seed --class=NewsSeeder
   ```
   
7. Sunucuyu başlatın:
   ```
   php artisan serve
   ```

> Not: Çok büyük veri oluşturulacağı için, işlem sırasında bilgisayarınızın kaynaklarını gözlemlemeniz önerilir.