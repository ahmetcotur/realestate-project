<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları doğrulama sınıfı tarafından kullanılan varsayılan
    | hata mesajlarını içerir. Bazı kuralların boyut gibi birden çok sürümü
    | bulunur. Bu mesajların her birini burada istediğiniz gibi düzenleyebilirsiniz.
    |
    */

    'accepted' => ':attribute kabul edilmelidir.',
    'accepted_if' => ':other :value olduğunda :attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute :date tarihinden sonraki bir tarih olmalıdır.',
    'after_or_equal' => ':attribute :date tarihinden sonra veya aynı tarihte olmalıdır.',
    'alpha' => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute sadece harfler, rakamlar, tireler ve alt çizgiler içerebilir.',
    'alpha_num' => ':attribute sadece harfler ve rakamlar içerebilir.',
    'array' => ':attribute bir dizi olmalıdır.',
    'ascii' => ':attribute yalnızca tek baytlık alfasayısal karakterler ve semboller içermelidir.',
    'before' => ':attribute :date tarihinden önceki bir tarih olmalıdır.',
    'before_or_equal' => ':attribute :date tarihinden önce veya aynı tarihte olmalıdır.',
    'between' => [
        'array' => ':attribute :min - :max arasında öğe içermelidir.',
        'file' => ':attribute :min - :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'string' => ':attribute :min - :max karakter arasında olmalıdır.',
    ],
    'boolean' => ':attribute doğru veya yanlış olmalıdır.',
    'can' => ':attribute alanı yetkisiz bir değer içeriyor.',
    'confirmed' => ':attribute tekrarı eşleşmiyor.',
    'current_password' => 'Şifre yanlış.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute :date ile aynı tarih olmalıdır.',
    'date_format' => ':attribute :format formatına uygun olmalıdır.',
    'decimal' => ':attribute :decimal ondalık basamağa sahip olmalıdır.',
    'declined' => ':attribute reddedilmelidir.',
    'declined_if' => ':other :value olduğunda :attribute reddedilmelidir.',
    'different' => ':attribute ve :other birbirinden farklı olmalıdır.',
    'digits' => ':attribute :digits rakam olmalıdır.',
    'digits_between' => ':attribute :min ile :max arasında rakam olmalıdır.',
    'dimensions' => ':attribute geçersiz resim boyutlarına sahip.',
    'distinct' => ':attribute alanı yinelenen bir değere sahip.',
    'doesnt_end_with' => ':attribute şunlardan biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute şunlardan biriyle başlamamalıdır: :values.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute şunlardan biriyle bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçili :attribute geçersiz.',
    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute alanı bir değer içermelidir.',
    'gt' => [
        'array' => ':attribute, :value öğeden fazla öğe içermelidir.',
        'file' => ':attribute, :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'string' => ':attribute, :value karakterden uzun olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute, :value veya daha fazla öğe içermelidir.',
        'file' => ':attribute, :value kilobayt veya daha büyük olmalıdır.',
        'numeric' => ':attribute, :value veya daha büyük olmalıdır.',
        'string' => ':attribute, :value karakter veya daha uzun olmalıdır.',
    ],
    'image' => ':attribute bir resim olmalıdır.',
    'in' => 'Seçili :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer' => ':attribute bir tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON dizisi olmalıdır.',
    'lowercase' => ':attribute küçük harf olmalıdır.',
    'lt' => [
        'array' => ':attribute, :value öğeden az öğe içermelidir.',
        'file' => ':attribute, :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'string' => ':attribute, :value karakterden kısa olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute, :value öğeden fazla öğe içermemelidir.',
        'file' => ':attribute, :value kilobayt veya daha küçük olmalıdır.',
        'numeric' => ':attribute, :value veya daha küçük olmalıdır.',
        'string' => ':attribute, :value karakter veya daha kısa olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute en fazla :max öğeye sahip olmalıdır.',
        'file' => ':attribute en fazla :max kilobayt olmalıdır.',
        'numeric' => ':attribute en fazla :max olmalıdır.',
        'string' => ':attribute en fazla :max karakter olmalıdır.',
    ],
    'max_digits' => ':attribute en fazla :max basamak içermelidir.',
    'mimes' => ':attribute, :values türünde bir dosya olmalıdır.',
    'mimetypes' => ':attribute, :values türünde bir dosya olmalıdır.',
    'min' => [
        'array' => ':attribute en az :min öğeye sahip olmalıdır.',
        'file' => ':attribute en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute en az :min olmalıdır.',
        'string' => ':attribute en az :min karakter olmalıdır.',
    ],
    'min_digits' => ':attribute en az :min basamak içermelidir.',
    'missing' => ':attribute alanı eksik olmalıdır.',
    'missing_if' => ':other :value olduğunda :attribute alanı eksik olmalıdır.',
    'missing_unless' => ':other :value olmadığı sürece :attribute alanı eksik olmalıdır.',
    'missing_with' => ':values var olduğunda :attribute alanı eksik olmalıdır.',
    'missing_with_all' => ':values var olduğunda :attribute alanı eksik olmalıdır.',
    'multiple_of' => ':attribute, :value katı olmalıdır.',
    'not_in' => 'Seçili :attribute geçersiz.',
    'not_regex' => ':attribute biçimi geçersiz.',
    'numeric' => ':attribute bir sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute en az bir harf içermelidir.',
        'mixed' => ':attribute en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers' => ':attribute en az bir rakam içermelidir.',
        'symbols' => ':attribute en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında görülmüştür. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => ':attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanı yasaktır.',
    'prohibited_if' => ':other :value olduğunda :attribute alanı yasaktır.',
    'prohibited_unless' => ':other :values içinde olmadıkça :attribute alanı yasaktır.',
    'prohibits' => ':attribute alanı :other alanının mevcut olmasını yasaklar.',
    'regex' => ':attribute biçimi geçersiz.',
    'required' => ':attribute alanı gereklidir.',
    'required_array_keys' => ':attribute alanı, :values için girişler içermelidir.',
    'required_if' => ':other :value olduğunda :attribute alanı gereklidir.',
    'required_if_accepted' => ':other kabul edildiğinde :attribute alanı gereklidir.',
    'required_unless' => ':other :values içinde olmadıkça :attribute alanı gereklidir.',
    'required_with' => ':values var olduğunda :attribute alanı gereklidir.',
    'required_with_all' => ':values var olduğunda :attribute alanı gereklidir.',
    'required_without' => ':values yokken :attribute alanı gereklidir.',
    'required_without_all' => ':values yokken :attribute alanı gereklidir.',
    'same' => ':attribute ve :other eşleşmelidir.',
    'size' => [
        'array' => ':attribute :size öğe içermelidir.',
        'file' => ':attribute :size kilobayt olmalıdır.',
        'numeric' => ':attribute :size olmalıdır.',
        'string' => ':attribute :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute şunlardan biriyle başlamalıdır: :values.',
    'string' => ':attribute bir metin olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute daha önceden kayıt edilmiş.',
    'uploaded' => ':attribute yüklemesi başarısız oldu.',
    'uppercase' => ':attribute büyük harf olmalıdır.',
    'url' => ':attribute geçerli bir URL olmalıdır.',
    'ulid' => ':attribute geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Özel Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Burada "attribute.rule" kuralını kullanarak öznitelikler için özel doğrulama
    | mesajları belirleyebilirsiniz. Bu, belirli bir öznitelik kuralı için hızlıca
    | özel bir dil satırı belirtmenizi sağlar.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Özel Doğrulama Öznitelikleri
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, öznitelik yer tutucumuzu "email" yerine
    | "E-Mail Adresi" gibi daha okunabilir bir şeyle değiştirmek için kullanılır.
    | Bu, mesajımızın daha açıklayıcı olmasına yardımcı olur.
    |
    */

    'attributes' => [],

]; 