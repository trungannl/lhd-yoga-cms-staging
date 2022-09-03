<div class='btn-group btn-group-sm'>
      <div class="dropdown">
          <a class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cog"></i>
          </a>
          <div class="dropdown-menu">
              <a data-toggle="tooltip" data-placement="bottom" title="detail" href="{{ route('users.show', $id) }}" class='btn btn-link'>
                  <i class="fa fa-info"></i>
                  Detail
              </a>

              <a data-toggle="modal" data-target="#modal-default" title="remove" href="javacript:void(0)" class='btn btn-link text-danger item_remove' onclick="removeItem({{ $id }})">
                  <i class="fa fa-trash"></i>
                  Remove
              </a>

              @if (!$active)
                  <a data-toggle="modal" data-target="#modal-active" title="active" href="javacript:void(0)" class='btn btn-link text-danger' onclick="activeItem({{ $id }}, 'active')">
                      <i class="fa fa-unlock"></i>
                      Active
                  </a>
              @else
                  <a data-toggle="modal" data-target="#modal-active" title="active" href="javacript:void(0)" class='btn btn-link text-danger' onclick="activeItem({{ $id }}, 'inactive')">
                      <i class="fa fa-lock"></i>
                      InActive
                  </a>
              @endif

          </div>
      </div>

</div>
