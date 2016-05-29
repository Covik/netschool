<section id="csf__subjects">
    <ul>
        @foreach($allPS as $ps)
            <li data-id="{{ $ps->subject->id }}" class="csf__subject{!! $ps->subject->id == $allPS->first()->subject->id ? ' csf__subject--active' : '' !!}">
                <span class="csf__subject__name">{{ $ps->subject->name }}</span>
                <span class="csf__subject__count"><div>{{ count($ps->subject->files()->where('class_id', '=', $class->id)->get()) }}</div></span>
            </li>
        @endforeach
    </ul>
</section><!--
--><section id="csf__files">
    <ul id="csf__files__list">
        @foreach($allPS as $ps)
            <li class="csf__subject__content" data-id="{{ $ps->subject->id }}">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Naziv</th>
                        <th>Opis</th>
                        <th>Autor</th>
                        <th>Preneseno</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>

                    <tbody class="files__list">
                        @forelse($ps->subject->files()->where('class_id', '=', $class->id)->get() as $file)
                            @include('home.files.single', ['file' => $file])
                        @empty
                            <tr class="table__empty">
                                <td colspan="5">Nema datoteka!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </li>
        @endforeach
    </ul>
</section>