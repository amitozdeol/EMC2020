  @extends('layouts.mail')

  @section('content')

    <div class="content" >
    <table>
      <tr>
        <td align="center">
          <h3 align="center">
            {{$log['datetime']}}
          </h3>
          <br>
          <p>
            <small>Application:</small>
          </p>
          <h3 align="center">
            <b>{{ $log['application_name'] }}</b>
          </h3>
          <hr>
          <p>
            <small>Log Type:</small>
          </p>
          <h3 align="center">
             <b>{{$log_type['name']}}</b>
          </h3>
          <hr>
          <h3 align="center">
            <b>{{ $log['report'] }}</b>
          </h3>
          <hr>
          <h3 align="center">
          @if(isset($building))
            <b>{{$system['name']}}</b> <br>
            <b>{{$building['address1']}}, {{$building['city']}}, {{$building['state']}}</b><br>
          @else
            You're receiving this message because you are subscribed to administrative {{$log_type['name']}} log
            notifications.
          @endif
          </h3>
        </td>
      </tr>
    <tr class="spacer">
      <td></td>
      <td></td>
      <td></td>
    </tr>
    </table>
    </div>

  @stop
