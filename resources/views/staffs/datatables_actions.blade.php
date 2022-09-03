<div class='btn-group btn-group-sm'>
      <div class="dropdown">
          <a class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cog"></i>
          </a>
          <div class="dropdown-menu">
              <a data-toggle="tooltip" data-placement="bottom" title="detail" href="{{ route('staffs.show', $id) }}" class='btn btn-link'>
                  <i class="fa fa-info"></i>
                  Detail
              </a>
              <a data-toggle="tooltip" data-placement="bottom" title="edit" href="{{ route('staffs.edit', $id) }}" class='btn btn-link'>
                  <i class="fa fa-edit"></i>
                  Edit
              </a>
              {!! Form::open(['route' => ['staffs.destroy', $id], 'method' => 'delete']) !!}
                  {!! Form::button('<i class="fa fa-trash"></i> Remove', [
                  'type' => 'submit',
                  'class' => 'btn btn-link text-danger',
                  'onclick' => "return confirm('Are you sure?')",
                  ]) !!}
              {!! Form::close() !!}

              {!! Form::open(['route' => ['staffs.active', $id], 'method' => 'post']) !!}
              @if (!$active)
                  {!! Form::button('<i class="fas fa fa-unlock"></i> Active', [
                  'type' => 'submit',
                  'class' => 'btn btn-link text-danger',
                  'onclick' => "return confirm('Are you sure?')"
                  ]) !!}
              @else
                  {!! Form::button('<i class="fas fa fa-lock"></i> InActive', [
                  'type' => 'submit',
                  'class' => 'btn btn-link text-danger',
                  'onclick' => "return confirm('Are you sure?')"
                  ]) !!}
              @endif
              {!! Form::close() !!}

          </div>
      </div>

</div>
