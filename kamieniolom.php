﻿<?php
  session_start();
  if(isset($_SESSION['zalogowany'])){
      if($_SESSION['zalogowany']!=true)
      {
          header('Location: logowanie.php');
      }
  }
  else{
  header('Location: logowanie.php');
  }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Osadnicy</title>
    <link rel="shortcut icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body class="body" onload="odliczanie();">
    <Header class="header">
    <div class="tlo">
        <br>
        <div class="tresc_tlo">
            <div id="naglowek_na_tle">
            <h1 id="naglowek"> Osadnicy</h1>
            </div>
        </div>
        </div>  
        
    </Header>
        <a href="wioska.php">
        <div class="powrot">Powrót</div></a>
        <div class="wylogowanie">
    <h2>
        <?php
       if(isset($_SESSION['login'])){
        echo "Użytkownik  ". $_SESSION['login'];
        ?>
        <a href="wyloguj.php">
            <div class="tilelink2">Wyloguj się
       </div>

        </a>
        <?php
        }
    ?>
    </h2>
        </div>
        <br><br><br>
        <h4>SUROWCE</h4>
        <div id="zegar"></div>
        <?php
        require_once "secure.php";
 
 $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

 if($polaczenie->connect_errno!=0)
 {
     echo "Error:".$polaczenie->connect_errno." Opis: ". $polaczenie->connect_error;
     //header('Location: index.php');
 }
 else
 {

$id_user=$_SESSION['id_sesji'];
$id_uczestnik_zalogowany=$_SESSION['id_zalogowanego_uczestnika'];
$id_meczu=$_SESSION['mecz_przeslany'];
$id_wioski=$_SESSION['id_wioski'];
$pobranyczas = "SELECT * FROM uczestnicy WHERE id=$id_uczestnik_zalogowany";
if($rezultat = @$polaczenie->query($pobranyczas))
{
    $ilu_userow = $rezultat->num_rows;
    $wiersz = $rezultat->fetch_assoc();
   
}
$sql = "SELECT * FROM budynki";
 if($rezultat2 = @$polaczenie->query($sql))
 {
     $ilu_userow = $rezultat->num_rows;
     $wiersz_ratusz = $rezultat2->fetch_assoc();
     $wiersz_gospoda = $rezultat2->fetch_assoc();
     $wiersz_tartak = $rezultat2->fetch_assoc();
     $wiersz_kuznia = $rezultat2->fetch_assoc();
     $wiersz_kamienilom = $rezultat2->fetch_assoc();
     $wiersz_dom = $rezultat2->fetch_assoc();
     $wiersz_blok = $rezultat2->fetch_assoc();
     $wiersz_kosciol = $rezultat2->fetch_assoc();
     $wiersz_koszary = $rezultat2->fetch_assoc();
     $wiersz_stajnia = $rezultat2->fetch_assoc();
     $wiersz_huta = $rezultat2->fetch_assoc();
     $wiersz_fabryka = $rezultat2->fetch_assoc();
     $wiersz_lotnisko = $rezultat2->fetch_assoc();
     $wiersz_uniwersytet = $rezultat2->fetch_assoc();
    
 }
 $sql = "SELECT * FROM wioska WHERE id=$id_wioski";
 if($rezultat3 = @$polaczenie->query($sql))
 {
     $wioska_budynki = $rezultat3->fetch_assoc();
     $pobranyczas=$wioska_budynki['czas'];
    $czas=time();
    $zmiana=$czas-(int)$pobranyczas;  
    $predkosc_zywnosc=$wioska_budynki['predkosc_zywnosc'];
    $predkosc_drewno=$wioska_budynki['predkosc_drewno'];
    $predkosc_kamien=$wioska_budynki['predkosc_kamien'];
    $predkosc_metal=$wioska_budynki['predkosc_metal'];
    $predkosc_zl=$wioska_budynki['predkosc_zl'];
 }

$sql = "UPDATE wioska SET zywnosc=zywnosc+ $zmiana*$predkosc_zywnosc, drewno =drewno+ $zmiana*$predkosc_drewno,
kamien=kamien+ $zmiana*$predkosc_kamien, metal=metal+ $zmiana*$predkosc_metal, pieniadze=pieniadze+ $zmiana*$predkosc_zl,
czas=$czas WHERE id=$id_wioski";
@$polaczenie->query($sql);
 }
 ?>
<script>
        window.onload = surowce_odliczac;
        var zywnosc_liczba = <?php echo $wioska_budynki['zywnosc']; ?>;
        var drewno_liczba = <?php echo $wioska_budynki['drewno']; ?>;
        var kamien_liczba = <?php echo $wioska_budynki['kamien']; ?>;
        var metal_liczba = <?php echo $wioska_budynki['metal']; ?>;
        var zl_liczba = <?php echo $wioska_budynki['pieniadze']; ?>;
        var predkosc_zywnosc = <?php echo $predkosc_zywnosc; ?>;
        var predkosc_drewno = <?php echo $predkosc_drewno; ?>;
        var predkosc_kamien = <?php echo $predkosc_kamien; ?>;
        var predkosc_metal = <?php echo $predkosc_metal; ?>;
        var predkosc_zl = <?php echo $predkosc_zl; ?>;
        
    function surowce_odliczac()
        {
            zywnosc_liczba = zywnosc_liczba+predkosc_zywnosc;
            drewno_liczba = drewno_liczba+predkosc_drewno;
            kamien_liczba = kamien_liczba+predkosc_kamien;
            metal_liczba = metal_liczba+predkosc_metal;
            zl_liczba = zl_liczba+predkosc_zl;
            document.getElementById("zywnosc").innerHTML = zywnosc_liczba ;
            document.getElementById("drewno").innerHTML = drewno_liczba ;
            document.getElementById("kamien").innerHTML = kamien_liczba ;
            document.getElementById("metal").innerHTML = metal_liczba ;
            document.getElementById("zl").innerHTML = zl_liczba ;
            setTimeout("surowce_odliczac()",1000);
        }
        function budynek(id){
    zmiennajava = id;
    console.log ( '#someButton was clicked' );
    document.getElementById("kolejne_przejscie").value = zmiennajava;
    document.getElementById("budynek_nazwa").submit();
}  
</script>
        <div class="surowce">
            <ul class="surowiec">
             <li>   Żywność:<div id="zywnosc"></div></li>
             <li>   Drewno:<div id="drewno"></div></li>
             <li>   Kamień:<div id="kamien"></div></li>
             <li>   Metal:<div id="metal"></div></li>
             <li>   Złotówki:<div id="zl"></div></li>
    </ul>
    </div>
    <div class="container">
    <br> 
        <div class="nazwa_budynku"> Kamieniołom poziom <?php echo $wioska_budynki['kamieniolom'] ?> </div>
        <?php
        $drewno=$wiersz_kamienilom['drewno'];
        $kamien=$wiersz_kamienilom['kamien'];
        $metal=$wiersz_kamienilom['metal'];
        $zlotowki=$wiersz_kamienilom['pieniadze'];
    if($wioska_budynki['kamieniolom']==1){
        $zlotowki=$zlotowki*2;
        $drewno=$drewno*2;
        $metal=$metal*2;
    }
    if($wioska_budynki['kamieniolom']==2){
        $zlotowki=$zlotowki*4;
        $drewno=$drewno*4;
        $metal=$metal*4;
    }
    if($wioska_budynki['kamieniolom']==3){
        $zlotowki=$zlotowki*8;
        $drewno=$drewno*8;
        $metal=$metal*8;
    }
    if($wioska_budynki['kamieniolom']==4){
        $zlotowki=$zlotowki*16;
        $drewno=$drewno*16;
        $metal=$metal*16;
    }
    if($wioska_budynki['kamieniolom']==5){
        $zlotowki=$zlotowki*32;
        $drewno=$drewno*32;
        $metal=$metal*32;
    }
    if($wioska_budynki['kamieniolom']==6){
        $zlotowki=$zlotowki*64;
        $drewno=$drewno*64;
        $metal=$metal*64;
    }
    if($wioska_budynki['kamieniolom']==7){
        $zlotowki=$zlotowki*128;
        $drewno=$drewno*128;
        $metal=$metal*128;
    }
    if($wioska_budynki['kamieniolom']==8){
        $zlotowki=$zlotowki*256;
        $drewno=$drewno*256;
        $metal=$metal*256;
    }
    if($wioska_budynki['kamieniolom']==9){
        $zlotowki=$zlotowki*512;
        $drewno=$drewno*512;
        $metal=$metal*512;
    }
    if($wioska_budynki['kamieniolom']<10){
        $sql = "SELECT * FROM budowa WHERE wioska=$id_wioski AND budynek='kamieniolom' AND zrobione=0";
        if($rezultat = @$polaczenie->query($sql))
{
    $ile_zdarzen = $rezultat->num_rows;
    if($ile_zdarzen==0){
    ?>
        <div class="budynek_budowa">
        <div class="b_budowa"> Następny poziom
        </div>       
        <ul class="koszty">
             <li>   Drewno <?php echo $drewno ?></li>
             <li>   Kamień <?php echo $kamien ?></li>
             <li>   Metal <?php echo $metal ?></li>
             <li>   Złotówki <?php echo $zlotowki ?></li>
        </ul> 
             <div class="budowa" onclick="budynek(id)" id="kamieniolom">ROZBUDUJ  </div>
        <form id="budynek_nazwa" action="budowa/budynek_rozbudowa.php" method="POST">
<input type="hidden" name="kolejne_przejscie"  id='kolejne_przejscie' required /><br />
<script>
document.getElementById("kolejne_przejscie").value = zmiennajava;</script>
</form>
    </div>
    </div>
   <?php
    }
    else{
        ?>
        <div class="budynek_budowa">
         Buduje się!
        </div> 
        <?php 
    }}
    }
    if($wioska_budynki['kamieniolom']==10){
        ?>
        <div class="b_podsumowanie"> Maksymalny poziom rozbodowy
        </div> 
        <?php
        $polaczenie->close(); 
    }
   ?>
    </div> 
  
    <br> <br> 
    
        <footer class="footer">
            <p><div id="tekst"></div> Filip Sawicki 2023</p>
        </footer>
    
</body>

</html>