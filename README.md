# Hakkında

Pozitim CI, projenizdeki testleri farklı PHP sürümleri ve farklı ortamlar için docker container üzerinden çalıştırmanızı sağlar.

# Gereksinimler

* PHP 5.6+
* MySQL
* Composer
* Docker
* Docker Compose

# Kurulum

## Veritabanı
Sunucuya gerekli servis ve uygulamaların kurulumu yapıldıktan MySQL sunucusu üzerinde **ci** isimli bir veritabanı oluşturmalısınız. Ardından aşağıdaki komut ile veritabanı şeması oluşturulmalıdır:

```bash
$ php bin/console.php migrations:migrate
```

## Ortam Ayarı
Web sunucusunda **APPLICATION_ENV** isimli ortam ayarı tanımlamalı. Ayrıca aynı isimle bir ayar dosyası **configs/env** klasörü altına eklenmeli. Örnek için **configs/env/development.php.sample** dosyasına göz atılabilir.

## Öntanımlı Docker İmajları
**dockerfiles/build.sh** dosyası ile öntanımlı docker imajları hazırlanmalıdır.

# Kullanım
Şu an için herhangi bir grafiksel arabirim bulunmamaktadır. Proje cli komutu üzerinden çalışmaktadır. compose:run komutu --project-file isimli bir parametre alır. Bu parametre proje kök dizinindeki project-ci.yml dosyasının nerede olduğunu belirtir.

```bash
php bin/console.php compose:run --project-file=/data/projects/auth/pozitim-ci.yml
```

# pozitim-ci.yml

Aşağıda örnek bir yaml dosyası içeriği bulunmaktadır:
```yaml
suite1:
  image: pozitim-ci/centos-php56
  environments:
    APPLICATION_ENV: pozitim-ci
    PHALCON_VERSION: 1.3.4
    NGINX_PUBLIC_FOLDER: /project/public
    NGINX_INDEX_FILE: index.php
  services:
    gearmand:
    mongo:
    redis:
    mysql:
      database: test
  commands:
    - "php -r \"echo Phalcon\\Version::getId();\""
  notifications:
    hipchat:
      room_name: Auth

suite2:
  extend: suite1
  image: pozitim-ci/centos-php56
  environments:
    APPLICATION_ENV: pozitim-ci
    PHALCON_VERSION: 2.0.0
```

## Suite İsimleri
Her bir test suite farklı isimle dosyada yer almalıdır. Örnek içerikteki suite1 ve suite2 test suite isimlerini ifade eder.

## Docker Imajları
**suite.image** ayarı ile tanımlanır. Öntanımlı docker imajları hazırlandıktan sonra bu ayar için şu imajlar kullanılabilir:

* pozitim-ci/centos-php53
* pozitim-ci/centos-php54
* pozitim-ci/centos-php55
* pozitim-ci/centos-php56
* pozitim-ci/centos-php70

Öntanımlı ayarlar haricinde kurulum yapılan sunucuda oluşturulan herhangi bir docker imajı da kullanılabilir.

## Ortam Ayarları
**suite.environments** ayarı ile tanımlanır. Docker Compose ayarlarındaki environment özelliği ile aynı işlevi görür. Şu an için özel tanımlı ayarlar şu şekildedir:

* APPLICATION_ENV: Docker imajı çalıştığında projedeki hangi uygulama ayarlarının kullanılması gerektiğini belirtir. Kullanımı zorunlu değil.
* PHALCON_VERSION: Docker imajı çalıştığında hangi phalcon sürümünün aktif olması gerektiğini belirtir. Kullanımı zorunlu değildir. Öntanımlı değeri: 2.1.x değerini alır. Lütfen ayrıntılar için Phalcon Desteği bölümüne göz atın.
* NGINX_PUBLIC_FOLDER: Docker imajı çalıştığında hangi dizinin NGINX için ana dizin olması gerektiği belirtir. Öntanımlı değeri: /project/public. Kullanımı zorunlu değildir.
* NGINX_INDEX_FILE: Docker imajı çalıştığında hangi dosyanın NGINX için ana dosya olması gerektiğini belirtir. Öntanımlı değeri: index.php. Kullanımı zorunlu değildir.

## Servisler
**suite.services** ayarı ile tanımlanır. Aşağıdaki servisler desteklenmektedir:

* memcached
* gearmand
* redis
* mysql: mysql.database ayarı ile veritabanı ismi ayarlanabilir. Mysql bağlantısı sırasında kullanıcı adı olarak root seçilmeli şifre alanı ise boş bırakılmalıdır.

## Test Komutları
**suite.commands** ayarı ile tanımlanır. Bu ayar birden çok komut alabilir. Komutlar sırası ile çalıştırılır. Komutlar eklenirken özel karakter varsa \ ile escape edilmeli ve "" içerisinde yazılmalı.

## Bildirimler
**suite.notifications** ayarı ile tanımlanır. Bu ayar birden çok bildirim türü alabilir.

# Phalcon Desteği
Aşağıdaki sürümler için phalcon desteği mevcuttur:

* 1.3.4 (PHP 7 desteği bulunmamakta)
* 2.0.0 (PHP 7 desteği bulunmamakta)
* 2.0.10 (PHP 7 desteği bulunmamakta)
* 2.1.x (PHP 5.3 desteği bulunmamakta)