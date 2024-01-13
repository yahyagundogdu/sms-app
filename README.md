## Sms App

Bu uygulama Sms App için hazırlanmış bir restful apidir.

## Uygulama İçin Özellştirilen / Kullanılan Yapılar

-   Stubs
-   Observer
-   Request / Rules

## Kullanılan Paketler

Bu uygulamanın backend tarafı için yüklenen 3. parti paketler aşağıdaki gibidir.

-   [Laravel Passport](https://laravel.com/docs/10.x/passport)
-   [Laravel Activity Log](https://github.com/spatie/laravel-activitylog)
-   [Browner12 Helpers](https://github.com/browner12/helpers)
-   [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger?tab=readme-ov-file)

## Projeye Eklenen Komutlar

> Uygulama Kurulumu için
>
> php artisan install

## Uygulamayı Nasıl Kurarız ?

.env.example dosyasını kopyalayarak aynı dizine yapıştırın ve adını .env olarak değiştin.

.env dosyasında

> DB_DATABASE -> yazan kısımda varsayılan bir veri tabanı ismi verilmiş fakat bunu istediğiniz gibi değiştirebilirsiniz.

> DB_USERNAME -> varsayılan olarak root seçilmiş kendi veri tabanı kullanıcı adınıza göre isimlendirebilirsiniz.

> DB_PASSWORD -> varsayılan olarak boş bıakılmış kendi veri tabanı kullanıcı adınıza göre kullanıcı adınız iççin oluşturduğunuz şifreyi yazabilirsiniz.

daha sonra terminalde

> composer install yapın

ardından terminalde

> php artisan install

komutunu yazın bu sizin için sms_app adında bir veri tabanı oluşturacaktır ve sizin için swager dökümanı oluşturacaktır.
Dökümana ulaşmak için tarayıcınızda http://127.0.0.1:8000/api/documentation adresine gidebilirsiniz.

## Not

Sms işlemlerini gönderdikten sonra Kuyruğu(Job) Çalıştırmak için Terminalde

> php artisan queue:work

komutunu çalıştırmanız gerekmektedir.

## Unit Test

Unit Test için varsayılan 500 adetlik bir test yazıldı bu testi çalıştırmak için

> php artisan test

komutunu terminalde çalıştırabilirsiniz

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
