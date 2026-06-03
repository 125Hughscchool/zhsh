<style>
    /* Государственная символика */
    .state-symbol {
        background-color: #003d5c;
        padding: 20px 0;
        border-bottom: 2px solid #FEC50C;
    }
    .state-symbol__inner {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .state-symbol__flag img,
    .state-symbol__emblem img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    .state-symbol__text {
        text-align: center;
        color: #ffffff !important;
    }
    .state-symbol__country,
    .state-symbol__country-kz {
        color: #ffffff !important;
        font-size: 18px;
        font-weight: bold;
        margin: 5px 0;
        line-height: 1.3;
    }
    .state-symbol__country-kz {
        font-size: 20px;
        color: #FEC50C !important;
    }
    .state-symbol__country {
        font-size: 16px;
    }
    
    /* Футер */
    footer,
    .footer,
    footer .footer__block,
    footer .footer__title,
    footer .footer__address,
    footer .footer__ul,
    footer .copyright,
    footer .copyright__text {
        color: #ffffff !important;
    }
    
    footer a,
    .footer a {
        color: #ffffff !important;
        text-decoration: none;
    }
    footer a:hover,
    .footer a:hover {
        color: #FEC50C !important;
        text-decoration: underline;
    }
    
    footer svg,
    footer svg *,
    footer .social__icon {
        fill: none !important;
        stroke: #ffffff !important;
        stroke-width: 2 !important;
    }
    
    footer .social__item {
        border: 2px solid #ffffff !important;
        display: inline-block;
        margin: 0 5px;
        padding: 5px;
        border-radius: 4px;
    }
    
    .copyright {
        background-color: #002a40;
        margin-top: 30px;
        padding: 15px 0;
        text-align: center;
    }
    .copyright,
    .copyright * {
        color: #ffffff !important;
    }
    
    /* Адаптивность */
    @media (max-width: 768px) {
        .state-symbol__inner {
            flex-direction: column;
            gap: 15px;
        }
        .state-symbol__country-kz {
            font-size: 18px;
        }
        .state-symbol__country {
            font-size: 14px;
        }
    }
</style>

<!-- Государственная символика -->
<div class="state-symbol">
    <div class="container">
        <div class="state-symbol__inner">
            <div class="state-symbol__flag">
                <img src="<?php echo BASE_URL; ?>assets/img/Flag_of_Kazakhstan.svg.png" alt="Флаг Республики Казахстан" width="80" height="50">
            </div>
            
            <div class="state-symbol__text">
                <div class="state-symbol__country-kz">Қазақстан Республикасы</div>
                <div class="state-symbol__country">Республика Казахстан</div>
            </div>
            
            <div class="state-symbol__emblem">
                <img src="<?php echo BASE_URL; ?>assets/img/gerb.png" alt="Герб Республики Казахстан" width="60" height="60">
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer__inner">
            <div class="footer__block">
                <h4 class="footer__title">125 HIGH SCHOOL TARAZ</h4> 
                <address class="footer__address">
                    <div>Тараз, Дмитрия Шостаковича көшесі, 4А</div>
                    <div>тел.: <a href="tel:+77764444125">+7 (776) 444-41-25</a></div>
                    <div>e-mail: <a href="mailto:info@kemel-urpaq.edu.kz">info@kemel-urpaq.edu.kz</a></div>
                </address> 
            </div>
            
            <div class="footer__block">
                <h4 class="footer__title">Әлеуметтік желілер</h4>
                <div class="social social--footer">
                    <a href="https://facebook.com/ваш_профиль" class="social__item" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <svg class="social__icon" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    
                    <a href="https://www.instagram.com/125highschool.taraz/" class="social__item" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <svg class="social__icon" width="24" height="24" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="footer__block">
                <h4 class="footer__title">Пайдалы сілтемелер</h4>
                <ul class="footer__ul">
                    <li class="ul__item"><a href="https://www.akorda.kz/kz" target="_blank">Президенттің үндеуі</a></li>
                    <li class="ul__item"><a href="https://www.gov.kz/memleket/entities/edu?lang=kk" target="_blank">Облыстық білім басқармасы</a></li>
                    <li class="ul__item"><a href="https://bookfund.kz/" target="_blank">Bookfund</a></li>
                    <li class="ul__item"><a href="https://digitallibrary.kz/" target="_blank">Digital Library</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="copyright">
        <div class="copyright__text">
            <div>Copyright 2023. Все права защищены</div>
            <div>DESIGN & DEVELOPMENT BY <span>PAINTXANT.KZ</span></div>
        </div>
    </div>
</footer>

<script src="assets/js/app.js"></script>
</body>
</html>