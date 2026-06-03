
<header class="header">
    <div class="header__left">

    </div>
    <div class="header__right">
        <nav class="nav">
            <ul class="nav__list">
                    <p>АДММНИСТРАТОР:</p>
                    <li class="nav__item">
                    <?php if (isset($_SESSION['name'])) { ?>

                        <a class="nav__link"><?php echo $_SESSION['name'];?></a>
                        <?php
                            } 
                            else {
                            echo 'Пользователь не найдено!'; 
                            }
                            ?>
                    </li>
                <li class="nav__item">
                    <a class="nav__link" href="<?php echo BASE_URL . "logout.php"; ?>">Выход</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
