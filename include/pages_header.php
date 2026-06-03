<?php
require __DIR__ . '/../path.php';
include __DIR__ . '/../topics.php';
$topicsAll = selectAll('topics');
$menuTree = buildTree($topicsAll, null); 

?>

<header class="header header-page" id="header">
    
<div class="header__inner">
            <div class="adress">
                <div class="phone">
                    <div class="introicon">
                        <a href="" class="introicon__item introicon__item--md" target="_blank">
                            <svg class="introicon__icon">
                                <use xlink:href="#email"> </use>
                            </svg>
                        </a>
                    </div>
                    <div class="adress__text"></div>  
                </div>
                <div class="email">
                    <div class="introicon">
                        <a href="" class="introicon__item introicon__item--md" target="_blank">
                            <svg class="introicon__icon">
                                <use xlink:href="#phone"> </use>
                            </svg>
                        </a>
                    </div>
                    <div class="adress__text"></div> 
                </div>
            </div>
            <nav class="nav">
                <a href="#" class="nav__link " data-scroll="#section"></a>
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="<?php echo BASE_URL ?>" class="nav__link">Басты бет</a>
                    </li>
                    
                    <?php renderNavMenu($menuTree); ?>
                </ul>
            </nav>
            <div class="introicon">
                        <a href="../account/signin.php" class="introicon__item introicon__item--md" target="_blank">
                            <svg class="introicon__icon">
                                <use xlink:href="#signin"> </use>
                            </svg>
                        </a>
                    </div>
            <button class="burger" type="button"><span class="burger__item">menu</span></button>
        </div>
</header>

