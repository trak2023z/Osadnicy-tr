<?php 
      require_once "../secure.php";
      session_start();
      $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
      $id_user=$_SESSION['id_sesji'];
      $id_uczestnik_zalogowany=$_SESSION['id_zalogowanego_uczestnika'];
      $id_meczu=$_SESSION['mecz_przeslany'];
      $id_wioski=$_SESSION['id_wioski'];

      $sql = "SELECT * FROM budynki";
 if($rezultat2 = @$polaczenie->query($sql))
 {
     $ilu_userow = $rezultat2->num_rows;
     $wiersz_ratusz = $rezultat2->fetch_assoc();
     $wiersz_gospoda = $rezultat2->fetch_assoc();
     $wiersz_tartak = $rezultat2->fetch_assoc();
     $wiersz_kuznia = $rezultat2->fetch_assoc();
     $wiersz_kamieniolom = $rezultat2->fetch_assoc();
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
      if($rezultat = @$polaczenie->query($sql))
      {
          $pobranywiersz = $rezultat->fetch_assoc();
          $pieniadze=$pobranywiersz['pieniadze'];
          $drewno=$pobranywiersz['drewno'];  
      }
      $koszt_podstawowy=(int)$wiersz_kuznia['pieniadze']; 
      $drewno_budowa_podstawowy=(int)$wiersz_kuznia['drewno']; 
      $koszt=$koszt_podstawowy*(2**$pobranywiersz['kuznia']);
      $drewno_budowa=$drewno_budowa_podstawowy*(2**$pobranywiersz['kuznia']);
      if($pieniadze>$koszt && $drewno>$drewno_budowa && $pobranywiersz['kuznia']<5){  
      $sql = "UPDATE wioska SET kuznia=kuznia+1, pieniadze=pieniadze-$koszt, drewno=drewno-$drewno_budowa WHERE id=$id_wioski";
      $polaczenie->query($sql);
      $sql = "SELECT kuznia, huta FROM wioska WHERE id=$id_wioski";
      if($rezultat = @$polaczenie->query($sql))
      {
          $ile_wiosek = $rezultat->num_rows;
          $predkosc_cala=0;
          $wiersz = $rezultat->fetch_assoc();
          $predkosc_wioskih=100*(2**($wiersz['huta']-1));
          $predkosc_wioskik=2**($wiersz['kuznia']-1);
          $predkosc_cala=$predkosc_cala+$predkosc_wioskih+$predkosc_wioskik;
          $sql = "UPDATE wioska SET predkosc_metal=$predkosc_cala WHERE id=$id_wioski";
          $polaczenie->query($sql); 
      }      
       }
       else{
        $_SESSION['surowce_malo']=1;
        header('Location: ../kuznia.php');
      }
      $polaczenie->close();
      header('Location: ../kuznia.php');
?>