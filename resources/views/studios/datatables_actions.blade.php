<div class='btn-group btn-group-sm'>
    <div class="dropdown">
        <a class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-cog"></i>
        </a>
        <div class="dropdown-menu">
            <a data-toggle="tooltip" data-placement="bottom" title="detail" href="{{ route('studios.show', $studio->id) }}" class='btn btn-link'>
                <i class="fa fa-info"></i>
                Detail
            </a>
            {!! Form::open(['route' => ['studios.destroy', $studio->id], 'method' => 'delete']) !!}
                {!! Form::button('<i class="fa fa-trash"></i> Remove', [
                    'type' => 'submit',
                    'class' => 'btn btn-link text-danger',
                    'onclick' => "return confirm('Are you sure?')",
                ]) !!}
            {!! Form::close() !!}
            
            @if ($studio->status !== $studio::OPEN)
                {!! Form::open(['route' => ['studios.active', $studio->id], 'method' => 'post']) !!}
                    {!! Form::button('<i class="fas fa fa-unlock"></i> Open', [
                        'type' => 'submit',
                        'class' => 'btn btn-link text-success',
                        'onclick' => "return confirm('Are you sure?')"
                    ]) !!}
                {!! Form::close() !!}
            @endif

            @if ($studio->status !== $studio::CLOSE)
                {!! Form::open(['route' => ['studios.cancel', $studio->id], 'method' => 'post']) !!}
                    {!! Form::button('<i class="fas fa fa-ban"></i> Close', [
                        'type' => 'submit',
                        'class' => 'btn btn-link text-danger',
                        'onclick' => "return confirm('Are you sure?')"
                    ]) !!}
                {!! Form::close() !!}
            @endif

        </div>
    </div>

</div>
