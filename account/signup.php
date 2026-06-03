<?php 
    
    include "../path.php"; 
    include  "../app/controllers/AuthUsers.php";
?>


<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/account.css">

	

	<title>регистрация</title>
</head>
<body>
    <main>    
    <h2 class="title__account">регистрация</h2>

            <div >
                <p class="err"><?="$errMsg"?></p>
            </div>


        <div class="container container--two">
        <div class="form__header">
                <!-- <a href="<?php echo BASE_URL ?>">
                    <img class="header__logo" src="../assets/img/logo/log.png" alt="My image">
                </a> -->
                <h2 class="header__title">«INNOVERSE SEMEY» ЖШС
жеке меншік
125 High School Семей</h2>
                <div class="address"><p>тел.:  </br> е-mail: </p></div>
                <ul class="form__footer--bb ">
                    <li>
                        <a href="signin.php">Кіру</a>
                    </li>
                    
                </ul>
            </div>
<!----profile---------------->
                   

                    <form class="form form--auht" action="signup.php" method="post">
                                <div class="cabinet">
                                    <div class="cabinet__form">    
                                        <div class="form-floating mb-3">
                                            <input name="name" type="text" class="form-control form-control--md" id="floatingInput" placeholder="name@example.com">
                                            <label for="floatingInput">Ф.И.О</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input name="email" type="email" class="form-control form-control--md" id="floatingInput" placeholder="name@example.com">
                                            <label for="floatingInput">Email </label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input name="pass_first" type="password" class="form-control form-control--md" id="floatingPassword" placeholder="Password">
                                            <label for="floatingPassword">Құпия сөз</label>
                                        </div>
                                        <div class="form-floating">
                                            <input name="pass_second" type="password" class="form-control form-control--md" id="floatingPassword" placeholder="Password">
                                            <label for="floatingPassword">Құпия сөзді қайталаңыз </label>
                                        </div>

                                        
                                    </div>
                                    
                                </div>

                                <div class="form__footer"> 
                                    <div class="form__group"> 
                                    <button type="submit" class="btn btn-primary" name="button-signup">Тіркелу</button>
                                    </div>
                                    
                                </div>
                    </form>
                   
		</div><!----container end--------------------->	

</main>
	
</body>
</html>