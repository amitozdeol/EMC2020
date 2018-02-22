<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Coming Soon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            line-height: 1.2;
            margin: 0;
        }
        html {
            color: #888;
            display: table;
            font-family: sans-serif;
            height: 100%;
            text-align: center;
            width: 100%;
        }
        body {
            display: table-cell;
            vertical-align: middle;
            margin: 2em auto;
        }
        h1 {
            color: #555;
            font-size: 2em;
            font-weight: 400;
        }
        h3 {
            color: #555;
            font-size: 1.2em;
            font-weight: 100;
        }
        p {
            margin: 8px auto;
            width: 500px;
            line-height: 1.4em
        }
        a,
        a:hover,
        a:visited {
            border-bottom: 1px solid;
            color: #444;
            font-size: 1.1em;
            text-decoration: none;
        }
        @media only screen and (max-width: 280px) {
            body, p {
                width: 95%;
            }
            h1 {
                font-size: 1.5em;
                margin: 0 0 0.3em;
            }
        }
        #output-trigger {
          position: absolute;
          bottom: 1em;
          right: 1em;
          font-size: 1.5em;
          cursor: pointer;
        }
        #output {
          max-height: 0;
          overflow: hidden;
          background: #E9E9E9;
          font-family: monospace;
          text-align: left;
          width: 75%;
          margin: 0 auto;
          padding: 0;
          transition: all 0.5s ease-in;
        }
        #output span {
          display: block;
          padding: 4px;
        }
        #output span:first-child {
          display: block;
          font-size: 1.5em;
          font-weight: 900;
          background: #6C6C6C;
          color: #EFEFEF;
          padding: 4px;
        }
        #output span:nth-child(even) {
          background: #D5D5D5;
        }
        #output span:hover {
          background: #6C6C6C;
          color: #EFEFEF;
        }
        #output.show {
          max-height: 100%;
          padding: 20px;
        }
    </style>
</head>
<body>
  @yield('message')

  @if(isset($exception))

    <div id="output-trigger">&#960;</div>

    <div id="output">
      @foreach(explode("\n", $exception) as $line)
        <span>{!!$line!!}<br></span>
      @endforeach
    </div>

  @endif

<script type="text/javascript">

  var trigger = document.getElementById('output-trigger');
  trigger.addEventListener('click', function(){
    document.getElementById('output').classList.add("show");
    trigger.remove();
  });

</script>

</body>
</html>
<!-- IE needs 512+ bytes: http://blogs.msdn.com/b/ieinternals/archive/2010/08/19/http-error-pages-in-internet-explorer.aspx -->