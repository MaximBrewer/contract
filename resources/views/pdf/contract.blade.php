<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            padding: 1rem 0 2rem;
            font-size: 13px;
            line-height: 1.2;
            font-family: Nunito, DejaVu Sans;
            box-sizing: border-box;
        }

        body>* {
            margin: 0 auto;
            max-width: 960px;
            width: 100%
        }

        .bordered {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .bordered td {
            border: 1px solid #000;
            padding: .4em;
        }

        p {
            margin: 0 0 1em;
        }
    </style>
</head>

<body>
    <div>
        <h1 style="text-align:center;">{{ $contract->contractTemplate->title }}</h1>
        <div style="margin:1em">
            <table style="width:100%">
                <tr>
                    <td style="width:50%"></td>
                    <td style="width:50%;text-align:right">{{ $contract->date }}</td>
                </tr>
            </table>
        </div>
        <div style="margin:1em 0 3em">{!! $contract->contractTemplate->text !!}</div>

        <div style="margin:1em 0 3em">
            <table style="width:100%;" class="bordered">
                <tr>
                    <td style="width:50%"><strong>Поставщик</strong></td>
                    <td style="width:50%"><strong>Покупатель</strong></td>
                </tr>
                <tr>
                    <td style="width:50%">{!! $contract->contractTemplate->proposer_header !!}</td>
                    <td style="width:50%">{!! $contract->acceptor_header !!}</td>
                </tr>
                <tr>
                    <td style="width:50%">
                        ИНН {{ $recipient->inn }} / КПП {{ $recipient->kpp }}<br/>
                        ОКПО {{ $recipient->okpo }} / ОГРН {{ $recipient->ogrn }}<br/>
                        {{ $recipient->legal_address }}<br/>
                        Тел. {{ $recipient->phone }}<br/>
                        mail. {{ $recipient->mail }} / cайт. {{ $recipient->site }}<br/>
                        Р/сч. {{ $recipient->rs }}<br/>
                        {{ $recipient->bank }}<br/>
                        К/сч. {{ $recipient->ks }}<br/>
                        БИК {{ $recipient->bik }}<br/>
                    </td>
                    <td style="width:50%">
                        ИНН {{ $recivier->inn }} / КПП {{ $recivier->kpp }}<br/>
                        ОКПО {{ $recivier->okpo }} / ОГРН {{ $recivier->ogrn }}<br/>
                        {{ $recivier->legal_address }}<br/>
                        Тел. {{ $recivier->phone }}<br/>
                        mail. {{ $recivier->mail }} / cайт. {{ $recivier->site }}<br/>
                        Р/сч. {{ $recivier->rs }}<br/>
                        {{ $recivier->bank }}<br/>
                        К/сч. {{ $recivier->ks }}<br/>
                        БИК {{ $recivier->bik }}<br/>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%">Подпись ___________/ __________________________</td>
                    <td style="width:50%">Подпись ___________/ __________________________</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>