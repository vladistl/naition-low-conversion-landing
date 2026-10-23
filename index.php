<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Первая помощь: практический курс для каждого</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="api/visit.php" defer></script>
    <script src="js/main.js" defer></script>
    <script src="js/tracker.js" defer></script>
</head>
<body>
    <header class="hero">
        <div class="container hero-content">
            <span class="hero-badge">Офлайн-курс · 14 ноября 2026</span>
            <h1>Первая помощь: практический курс для каждого</h1>
            <p class="hero-text">
                Один день практики на манекенах — и вы знаете, что делать при остановке сердца,
                кровотечении, травме или ожоге до приезда скорой.
            </p>
            <div class="hero-cta">
                <button type="button" class="btn btn-register btn-hero">Записаться на курс — 4 900 ₽</button>
                <span class="hero-cta-note">Заявка без оплаты · осталось 5 мест из 14 · сертификат</span>
            </div>
            <div class="meta-grid">
                <div class="meta-card">
                    <strong>Дата</strong>
                    14 ноября 2026, суббота
                </div>
                <div class="meta-card">
                    <strong>Время</strong>
                    10:00 – 18:00, перерыв на обед
                </div>
                <div class="meta-card">
                    <strong>Место</strong>
                    Москва, ул. Примерная, 10, учебный центр «Спаси-Себя»
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="section">
            <div class="container">
                <h2 class="section-title">О курсе</h2>
                <p class="section-lead">
                    Это не лекция «для галочки», а полноценный практический день, где каждый блок
                    заканчивается отработкой навыков под контролем инструктора.
                </p>
                <div class="features-grid">
                    <article class="feature-card">
                        <h3>8 часов практики</h3>
                        <p>Больше половины времени посвящено тренировкам на манекенах, имитации травм и работе в парах.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Формат offline</h3>
                        <p>Живое общение, мгновенная обратная связь и возможность задать вопросы по вашим реальным ситуациям.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Сертификат</h3>
                        <p>После курса вы получите именной сертификат и чек-лист действий для дома, работы и поездок.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Малые группы</h3>
                        <p>До 14 человек в группе, чтобы каждый успел отработать все ключевые навыки несколько раз.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section pricing-section">
            <div class="container">
                <h2 class="section-title">Тарифы</h2>
                <p class="section-lead">
                    Базовый тариф — это весь учебный день целиком. Расширенный и корпоративный — по желанию.
                </p>
                <div class="pricing-grid">
                    <article class="pricing-card featured">
                        <span class="pricing-badge">Полный курс</span>
                        <h3>Базовый</h3>
                        <div class="price">4 900 ₽</div>
                        <ul class="pricing-list">
                            <li>Участие в полном однодневном курсе</li>
                            <li>Сертификат и памятка</li>
                            <li>Кофе-брейки</li>
                        </ul>
                        <button type="button" class="btn btn-register">Записаться</button>
                    </article>
                    <article class="pricing-card">
                        <h3>Расширенный</h3>
                        <div class="price">7 900 ₽</div>
                        <ul class="pricing-list">
                            <li>Всё из базового тарифа</li>
                            <li>Набор перевязочных материалов</li>
                            <li>Дополнительный практический блок</li>
                        </ul>
                        <button type="button" class="btn btn-register">Записаться</button>
                    </article>
                    <article class="pricing-card">
                        <h3>Корпоративный</h3>
                        <div class="price">12 900 ₽</div>
                        <ul class="pricing-list">
                            <li>Индивидуальный разбор рисков профессии</li>
                            <li>Консультация для HR или руководителя</li>
                            <li>Отчёт о прохождении для работодателя</li>
                        </ul>
                        <button type="button" class="btn btn-register">Записаться</button>
                    </article>
                </div>
            </div>
        </section>

        <section class="section registration-section" id="registration">
            <div class="container">
                <div class="registration-panel">
                    <h2 class="section-title">Записаться на курс</h2>
                    <p class="section-lead">
                        14 ноября, 10:00–18:00 · 4 900 ₽ · оплата не сейчас — перезвоним в течение дня и подтвердим место.
                    </p>
                    <form class="form-grid" id="registration-form" action="api/submit.php" method="post">
                        <label>
                            Имя
                            <input type="text" name="name" required autocomplete="name">
                        </label>
                        <label>
                            Телефон
                            <input type="tel" name="phone" required autocomplete="tel">
                        </label>
                        <label>
                            E-mail
                            <input type="email" name="email" required autocomplete="email">
                        </label>
                        <label class="field-wide">
                            Цель прохождения курса
                            <textarea name="purpose" required rows="2" placeholder="Пары слов достаточно: например, «помогать близким»"></textarea>
                        </label>
                        <button type="submit" class="btn field-wide">Отправить заявку</button>
                    </form>
                    <p class="form-message" id="form-message" aria-live="polite"></p>
                    <div class="reviews">
                        <blockquote>«Через месяц после курса помогла соседу с ожогом — руки сами вспомнили порядок действий». <cite>Анна, 34 года</cite></blockquote>
                        <blockquote>«Наконец-то не лекция, а практика: СЛР делали раз десять». <cite>Игорь, 41 год</cite></blockquote>
                        <blockquote>«Ходили всем отделом — инструкторы объясняют просто и спокойно». <cite>Мария, HR</cite></blockquote>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="section-title">Программа курса</h2>
                <p class="section-lead program-intro">
                    Программа выстроена от базовой оценки ситуации до сложных сценариев с несколькими
                    пострадавшими. Мы подробно разбираем алгоритмы, типичные ошибки и то, как
                    действовать, когда рядом нет медикаментов и оборудования. Каждый модуль включает
                    теорию, демонстрацию инструктора и практику участников.
                </p>
                <div class="program-list">
                    <article class="program-module">
                        <h3>10:00 – 11:00 · Оценка обстановки и безопасность</h3>
                        <p>Первый час посвящён тому, как не бросаться помогать вслепую и не попасть самому под угрозу.</p>
                    </article>
                    <article class="program-module">
                        <h3>11:00 – 12:30 · Сердечно-легочная реанимация</h3>
                        <p>Это центральный блок курса: полтора часа интенсивной практики по алгоритму СЛР для взрослых.</p>
                    </article>
                    <article class="program-module">
                        <h3>12:30 – 13:30 · Обед и разбор кейсов</h3>
                        <p>После насыщенного утреннего блока — пауза и спокойный разбор реальных историй из практики инструкторов.</p>
                    </article>
                    <article class="program-module">
                        <h3>13:30 – 15:00 · Кровотечения и шок</h3>
                        <p>После обеда переходим к одной из самых частых причин смертности при травмах — наружным кровотечениям.</p>
                    </article>
                    <article class="program-module">
                        <h3>15:00 – 16:30 · Переломы, вывихи, ожоги</h3>
                        <p>В этом модуле мы разбираем травмы, с которыми люди чаще всего сталкиваются дома, на улице, на работе и в спорте.</p>
                    </article>
                    <article class="program-module">
                        <h3>16:30 – 18:00 · Итоговая практика и сертификация</h3>
                        <p>Завершающий блок — это уже не отдельные навыки, а цельные сценарии, как в реальной жизни.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="section-title">Виды травм и состояний</h2>
                <p class="section-lead">
                    На курсе мы разбираем самые частые состояния, с которыми сталкиваются очевидцы
                    в городе, дома, на работе и в путешествиях.
                </p>
                <div class="injury-grid">
                    <article class="injury-card">
                        <h3>Кровотечения</h3>
                        <p>Артериальные, венозные и капиллярные кровотечения, давящая повязка, турникет, контроль после остановки крови.</p>
                    </article>
                    <article class="injury-card">
                        <h3>Переломы и вывихи</h3>
                        <p>Признаки перелома, иммобилизация, транспортная шина, ошибки при перемещении пострадавшего.</p>
                    </article>
                    <article class="injury-card">
                        <h3>Ожоги</h3>
                        <p>Термические и химические ожоги, охлаждение, стерильная повязка, когда нельзя снимать одежду с места ожога.</p>
                    </article>
                    <article class="injury-card">
                        <h3>Обмороки и шок</h3>
                        <p>Признаки шока, положение тела, контроль дыхания, согревание, что нельзя давать пострадавшему.</p>
                    </article>
                    <article class="injury-card">
                        <h3>Остановка дыхания</h3>
                        <p>СЛР, работа в паре, использование автоматического дефibrиллятора, действия до приезда скорой.</p>
                    </article>
                    <article class="injury-card">
                        <h3>Травмы головы и позвоночника</h3>
                        <p>Подозрение на травму шеи и спины, когда нельзя менять положение, фиксация головы и ожидание медиков.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section legal-section">
            <div class="container">
                <h2 class="section-title">Юридические аспекты</h2>
                <p class="section-lead">
                    Многие люди боятся помогать из-за страха юридической ответственности. На курсе
                    мы отдельно разбираем, что говорит закон и как действовать правильно.
                </p>
                <div class="legal-block">
                    <h3>Добросовестный помощник</h3>
                    <p>
                        В России действует принцип добросовестного помощника: если вы помогаете пострадавшему
                        в разумных пределах своих возможностей и без грубой неосторожности, закон защищает
                        вас от необоснованных претензий.
                    </p>
                </div>
                <div class="legal-block">
                    <h3>Границы ответственности</h3>
                    <p>
                        Мы разбираем, какие действия считаются разумными, когда нужно дождаться медиков,
                        и как фиксировать обстоятельства происшествия, если вы оказались свидетелем или участником помощи.
                    </p>
                </div>
                <div class="legal-block">
                    <h3>Документирование и вызов служб</h3>
                    <p>
                        Как правильно передать информацию диспетчеру, что сообщить медикам по прибытии и
                        какие данные важно сохранить для дальнейшего разбирательства или страхового случая.
                    </p>
                </div>
            </div>
        </section>

        <section class="emotional-photo">
            <img src="images/cpr-training.jpg" alt="Практика сердечно-легочной реанимации на манекене">
        </section>

        <section class="section">
            <div class="container">
                <h2 class="section-title">Инструкторы</h2>
                <p class="section-lead">
                    Курс ведут специалисты с многолетним опытом работы в экстренной медицине и обучения.
                </p>
                <div class="instructors-grid">
                    <article class="instructor-card">
                        <div class="instructor-photo">АК</div>
                        <h3>Алексей Кравцов</h3>
                        <p class="instructor-title">Врач скорой медицинской помощи, стаж 14 лет</p>
                        <ul class="credentials">
                            <li>Более 8 000 выездов бригады скорой помощи</li>
                            <li>Сертификат European Resuscitation Council BLS</li>
                            <li>Автор программы «Первая помощь дома и на работе»</li>
                        </ul>
                    </article>
                    <article class="instructor-card">
                        <div class="instructor-photo">МС</div>
                        <h3>Марина Соколова</h3>
                        <p class="instructor-title">Инструктор РКК, фельдшер, стаж 11 лет</p>
                        <ul class="credentials">
                            <li>Обучила более 1 200 слушателей базовой первой помощи</li>
                            <li>Член региональной команды инструкторов РКК</li>
                            <li>Специализация: помощь детям и подросткам</li>
                        </ul>
                    </article>
                    <article class="instructor-card">
                        <div class="instructor-photo">ДН</div>
                        <h3>Дмитрий Новиков</h3>
                        <p class="instructor-title">Парамедик, наставник учебного центра</p>
                        <ul class="credentials">
                            <li>Сертификат ERC First Aid Provider</li>
                            <li>Опыт работы в корпоративных программах безопасности</li>
                            <li>Ведёт практические блоки по кровотечениям и травмам</li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>


    </main>

    <footer class="site-footer">
        <div class="container">
            © 2026 Учебный центр «Спаси-Себя». Курс первой помощи, Москва.
        </div>
    </footer>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=113689204', 'ym');

    ym(113689204, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/113689204" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
</body>
</html>
