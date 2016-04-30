@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.widgets.articleStart', ['color'	=>	'#222'])
       <tr>
            <td class="title">
                Cheerio {{ $user->name }} !
            </td>
        </tr>
        <tr>
            <td width="100%" height="10"></td>
        </tr>
        <tr>
            <td class="title">
                Sad to see you go
            </td>
        </tr>
        <tr>
            <td class="paragraph">
                {{ $message }}
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>

        <tr>
            <td>
                @include('beautymail::templates.minty.button', ['text' => 'Return to the server', 'link' => Config('app.url')])
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
@stop
