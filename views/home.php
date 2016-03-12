<!DOCTYPE html>
<html>
    <head>
        <title>Holiday API</title>
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
                    <h1>Holiday API</h1>
                    <img class="logo" src="/img/logo.png">
                </header>
                <div class="well lead">
                    The goal of this project is to provide a comprehensive &amp; free API for obtaining information about holidays.
                </div>

                <div class="well">
                    <h2>Usage example</h2>
                    <p>To retrieve a list of holidays, simply make a GET request to <a href="/v1/holidays?country=US&year=<?= date('Y'); ?>&month=<?= date('m'); ?>" target="_blank">/v1/holidays</a></p>
                </div>

                <div class="well">
                    <h3>Parameters</h3>

                    <h4>Required</h4>
                    <div class="row">
                        <div class="col-xs-3"><code>country</code></div>
                        <div class="col-xs-9"><a href="http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">ISO 3166-1 alpha-2</a> format (BE, BG, BR, CA, CZ, DE, ES, FR, GB, GT, NL, NO, PL, SI, SK or US)</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><code>year</code></div>
                        <div class="col-xs-9"><a href="http://en.wikipedia.org/wiki/ISO_8601#Years" target="_blank">ISO 8601</a> format (CCYY)</div>
                    </div>

                    <h4>Optional</h4>
                    <div class="row">
                        <div class="col-xs-3"><code>month</code></div>
                        <div class="col-xs-9">1 or 2 digit month (1-12)</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><code>day</code></div>
                        <div class="col-xs-9">1 or 2 digit day (1-31 depending on the month)</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><code>previous</code></div>
                        <div class="col-xs-9">returns the previous holidays based on the date</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><code>upcoming</code></div>
                        <div class="col-xs-9">returns the upcoming holidays based on the date</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><code>pretty</code></div>
                        <div class="col-xs-9">prettifies returned results</div>
                    </div>
                </div>

                <div class="well">
                    <h3>Status Codes</h3>
                    <div class="row">
                        <div class="col-xs-3"><code>300</code></div>
                        <div class="col-xs-9">No one goof’d</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><code>400</code></div>
                        <div class="col-xs-9">You dun goof’d</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><code>500</code></div>
                        <div class="col-xs-9">We dun goof’d</div>
                    </div>
                </div>

                <div class="well">
                    <h3>Response</h3>
                    <pre>{
  "status": 200,
    "holidays": [{
      "name": "Star Wars Day",
      "country": "US",
      "date": "2013-05-04"
    }]
}</pre>
                </div>

                <div class="well">
                    <h3>Want to help?</h3>
                    <p>You can contribute by <a href="https://github.com/joshtronic/holidayapi.com" target="_blank">forking this project</a> and adding your country’s holiday list, each country is a JSON file. If you have any suggestions or find a bug, <a href="https://github.com/joshtronic/holidayapi.com/issues">please take the time to tell us</a>.</p>
                </div>

                <div class="well">
                    <h3>Contributors</h3>
                    <p>Holiday API was conceived and coded by <a href="http://joshtronic.com">Josh Sherman</a> and is provided as a free service. Special thanks to <a href="https://github.com/LGnap">LGnap</a>, <a href="https://github.com/WanderingZombie">John Nicholls</a>, <a href="https://github.com/GA114">Antonio Gurgel</a>, <a href="https://github.com/azzlack">Ove Andersen</a>, <a href="https://github.com/luisreyes">Luis Reyes</a>, <a href="https://github.com/MPomeroy">Mason Pomeroy</a>, <a href="https://github.com/calvera">Karel Sommer</a>, <a href="https://github.com/Sazed">Sazed</a>, <a href="https://github.com/dochardi">Kai</a> and <a href="https://github.com/kleewho">Łukasz Klich</a> for contributing additional country files. Website and logo artfully designed by <a href="https://twitter.com/thegeoffoliver" target="_blank">Geoff Oliver</a> of <a href="http://www.plan8studios.com/">Plan8 Studios</a>. Holiday API is <a href="https://www.linode.com/?r=5f682793582e82ce686747c851b998dc1f86a55b" target="_blank">proudly hosted by Linode</a>.</p>
                </div>
                <footer class="lead">
                    Built with <span class="heart">&hearts;</span> by <a href="http://joshtronic.com/?ref=holidayapi">@joshtronic</a>
                </footer>
            </div>
        </div>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-46049009-1', 'holidayapi.com');
            ga('send', 'pageview');
        </script>
    </body>
</html>
