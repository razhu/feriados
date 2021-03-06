<!DOCTYPE html>
<html>
    <head>
        <title>Feriados API</title>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/styles.min.css">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <script src="//load.sumome.com/" data-sumo-site-id="54926a714a38c69d4b5402706a836ffaab038eea6a977d2445eee4623d9712c9" async></script>

    </head>
    <body>
        <div class="container">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <header>
                    <h1>Feriados API</h1>
                    <img class="logo" src="/img/logo.png" style="width:250px; height:250px;">
                </header>
                <div class="well lead">

                    Esta API retorna las fechas de los feriados de Bolivia. Tomando en cuenta que si un feriado es domingo, recorre un día


                </div>
                <div>
                                                        <p>
                    Ejemplo para el mes de Agosto (08)
                    </p>
                        <p>
                        <a href="http://192.168.20.219:3000/v1/feriados?pais=BO&ano=2016&mes=05" target="_blank">http://192.168.20.219:3000/v1/feriados?pais=BO&ano=2016&mes=05</a>
                        </p>
                        <pre>
{
  "status": 200,
  "feriados": [
    {
      "fecha": "2016-05-02",
      "nombre": "Día del Trabajo"
    },
    {
      "fecha": "2016-05-26",
      "nombre": "Corpus Christi"
    }
  ]
}
                         </pre>   
                    </div>


                <footer class="lead">
                    Por <span class="heart">&hearts;</span><a href="https://agetic.gob.bo">AGETIC</a>
                </footer>
            </div>
        </div>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </body>
</html>
