## Kurulum

1. Repoyu klonlayın:
   ```
   git clone <repo-link>
   cd <repo-klasörü>
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

5. Migration ve seed işlemlerini başlatın:
   ```
   php artisan migrate --seed
   ```

6. Storage link oluşturun:
   ```
   php artisan storage:link
   ```

7. Sunucuyu başlatın:
   ```
   php artisan serve
   ```

> Not: Seeder otomatik olarak 250.000 haber kaydı oluşturacaktır. Bu işlem sisteminize göre zaman alabilir.