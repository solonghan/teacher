<div style="width: 205mm;">
    <table style="width: 100%;" border="0">
        <tr>
            <td>
                <img src="{{ env('APP_URL')."/assets/images/logo.png" }}" style="width: 100%; max-width: 160px;" width="160">
            </td>
            
        </tr>
    </table>
    <br>
    <div style="font-size: 18px; text-align:left;">
    {!! $msg !!}
    </div>
    
</div>
<script>
@if (isset($is_print))
    print();
@endif
</script>