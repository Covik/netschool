<tr data-crud-id="{{ $file->id }}">
    <td>{{ $file->filename }}</td>
    <td data-crud-ref="description">{{ $file->description }}</td>
    @if(isset($myfiles))
        @if($au->isAdmin() || $au->isProfessor())<td>{{ $file->theClass->name }}</td>@endif
        <td>{{ $file->subject->name }}</td>
    @endif
    @if(!isset($myfiles) || $au->isAdmin())<td{!! $file->user->isProfessor() ? ' class="csf__file__author__professor"' : '' !!}>{{ $file->user->name }}</td>@endif
    <td>{{ $file->created_at->format('d.m.Y. H:i') }}</td>
    <td data-crud-actions>
        <a href="{{ $file->getDownload() }}" download class="csf__file__download"><i class="glyphicon glyphicon-cloud-download"></i></a>
        @if($file->canModify())
            <button class="crud-modify crud-button"><i class="glyphicon glyphicon-pencil"></i></button><!--
            -->@if(!$au->isStudent())<button class="crud-delete crud-button"><i class="glyphicon glyphicon-remove"></i></button>@endif
        @endif
    </td>
</tr>