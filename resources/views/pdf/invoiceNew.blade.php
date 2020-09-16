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

        hr {
            border-color: #000;
        }

        h1 {
            border-bottom: 2px solid #000;
            padding-bottom: .5rem;
        }

        strong.underlined {
            display: block;
            border-bottom: 2px solid #000;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            margin: 0 auto;
            margin-bottom: 1.5rem;
        }

        table td,
        table th {
            border: 1px solid #000;
            padding: 0.15rem 0.5rem;
        }

        table tr:first-child td:nth-child(1) {
            width: 55%;
        }

        table tr:first-child td:nth-child(2) {
            min-width: 15%;
        }

        table tr:first-child td:nth-child(3) {
            min-width: 30%;
        }

        table.s-table tr:first-child td:nth-child(1) {
            text-align: center;
            width: 5%;
        }

        table.s-table tr:first-child td:nth-child(2) {
            width: 50%;
        }

        table.s-table tr:first-child td:nth-child(3) {
            text-align: center;
            width: 10%;
        }

        table.s-table tr:first-child td:nth-child(4) {
            text-align: center;
            width: 10%;
        }

        table.s-table tr:first-child td:nth-child(5) {
            text-align: right;
            width: 120px;
        }

        table.s-table {
            margin-bottom: 0;
        }

        table.s-table thead {
            border-top: 3px solid #000;
            border-left: 3px solid #000;
            border-right: 3px solid #000;
        }

        table.s-table tbody tr td {
            text-align: right
        }

        table.s-table tbody tr:first-child td:nth-child(1),
        table.s-table tbody tr:first-child td:nth-child(2),
        table.s-table tbody tr:first-child td:nth-child(3),
        table.s-table tbody tr:first-child td:nth-child(4) {
            text-align: center
        }

        table.s-table td:last-child {
            border-right: 3px solid #000;
        }

        table.s-table tbody tr:first-child td:first-child {
            border-left: 3px solid #000;
        }

        table.s-table tr:nth-child(2) td {
            border-top: 3px solid #000;
            border-bottom: none;
            border-right: none;
            border-left: none;
        }

        table.s-table tr:nth-child(3) td {
            border: none;
        }

        table.s-table tr:nth-child(4) td {
            border: none;
        }

        table.s-table tr:nth-child(2) td:last-child {
            border: 1px solid #000;
            border-top: 3px solid #000;
        }

        table.s-table tr:nth-child(3) td:last-child {
            border: 1px solid #000;
        }

        table.s-table tr:nth-child(4) td:last-child {
            border: 1px solid #000;
        }

        table.s-table tr:first-child td:nth-child(6) {
            text-align: right;
            width: 120px;
        }

        table tr.result td:first-child {
            text-align: right;
        }

        table tr.result td:last-child {
            text-align: right;
        }

        dl {
            padding-bottom: 0.3rem;
            display: block;
        }

        dl:after {
            content: "";
            display: block;
            clear: both;
        }

        dt {
            width: 10%;
            float: left;
        }

        dd {
            width: 80%;
            font-weight: 700;
            float: left;
        }

        p strong {
            display: inline-block;
        }

        div.stamp {
            position: relative;
        }

        div.stamp img {
            position: absolute;
            left: 170px;
            top: -10px;
        }

        .dir {
            display: inline-block;
            padding-left: 280px;
        }

        .buh {
            padding-top: 18px;
        }
    </style>
</head>

<body>
    <div>
        <table cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td colspan="2" rowspan="2">
                        {{ $recipient->bank }}
                        Банк получателя
                    </td>
                    <td>БИК</td>
                    <td rowspan="2">
                        {{ $recipient->bik }}<br />
                        {{ $recipient->ks }}
                    </td>
                </tr>
                <tr>
                    <td>Сч. №</td>
                </tr>
                <tr>
                    <td>ИНН {{ $recipient->inn }}</td>
                    <td>КПП {{ $recipient->kpp }}</td>
                    <td rowspan="2">Сч. №</td>
                    <td rowspan="2">{{ $recipient->rs }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        {{ $recipient->title }}<br />
                        Получатель
                    </td>
                </tr>
            </tbody>
        </table>
        <h1>Счет на оплату № {{ $settlement->id }} от {{ $settlement->date }}</h1>
        <dl>
            <dt>Поставщик</dt>
            <dd>
                {{ $recipient->title }}, ИНН
                {{ $recipient->inn }}, КПП {{ $recipient->kpp }}, {{ $recipient->legal_address }}
            </dd>
        </dl>
        <dl>
            <dt>Грузо- отправитель</dt>
            <dd>
                {{ $recipient->title }}, ИНН
                {{ $recipient->inn }}, КПП {{ $recipient->kpp }}, {{ $recipient->legal_address }}
            </dd>
        </dl>
        <dl>
            <dt>Покупатель</dt>
            <dd>{{ $settlement->creq }}
            </dd>
        </dl>
        <dl>
            <dt>Грузо- получатель</dt>
            <dd>{{ $settlement->creq }}
            </dd>
        </dl>
        <table cellpadding="0" cellspacing="0" class="s-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Наименование товара, работ, услуг</th>
                    <th>Ед.</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $auction->product->title }}</td>
                    <td>{{ $auction->multiplicity->title }}</td>
                    <td>{{ $bet->volume }}</td>
                    <td>{{ $bet->correct }}</td>
                    <td>{{ $settlement->balance }}
                    </td>
                </tr>
                <tr class="result">
                    <td colspan="5">Итого:</td>
                    <td>{{ $settlement->balance }}
                    </td>
                </tr>
                <tr class="result">
                    <td colspan="5">в том числе НДС, {{ str_replace("w", "", $recipient->nds) }}%</td>
                    <td>{{ $settlement->balance - round($settlement->balance / (100 + str_replace("w", "", $recipient->nds)) * 100, 2) }}
                    </td>
                </tr>
                <tr class="result">
                    <td colspan="5">Всего к оплате:</td>
                    <td>{{ $settlement->balance }}</td>
                </tr>
            </tbody>
        </table>
        <p>Всего наименований 1, на сумму {{ $settlement->stextsum }}</p>
        <strong class="underlined">{{ $settlement->textsum }}</strong>
        <br /><br /><br />
        <div class="stamp">
            <p>
                <strong>Руководитель:</strong> <span class="dir"></span>
            </p>
            <p class="buh"><strong>Главный бухгалтер:</strong></p>
        </div>
    </div>
</body>

</html>