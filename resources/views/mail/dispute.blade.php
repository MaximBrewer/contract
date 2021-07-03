<p>{{ $dispute->message }}</p>
<p>&nbsp;</p>
<p>Если вы еще не принимали участие в диспутах(целевое общение), пожалуйста посмотрите видео <a
        href="https://youtu.be/HDaUscdF0aY">Что такое ДИСПУТ(Целевое общение) и как он работает</a></p>
<p><a href="https://project.cross-contract.ru/personal/disputes/{{ $dispute->id }}">Перейти к диспуту между компаниями
        {{ $dispute->contragents[0]->title }} и {{ $dispute->contragents[1]->title }} </a></p>