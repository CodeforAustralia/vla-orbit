
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>{{ (isset($user) ? 'Edit' : 'New') }} User</div>
        </div>
        <div class="portlet-body form">

            <form method="POST" action="/user{{ (isset($user) ? '/update' : '') }}" class="form-horizontal">

                <div class="form-body">
                    {{ csrf_field() }}

                    <input type="text" class="form-control hidden" id="id" name="id" value="{{ (isset($user) ? $user->id : 0) }}" required>

                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Name</label>

                        <div class="col-md-4">
                            <input type="text" class="form-control" id="name" name="name" value="{{ (isset($user) ? $user->name : '') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-md-3 control-label">Email</label>

                        <div class="col-md-4">
                            <input type="email" class="form-control" id="email" name="email" value="{{ (isset($user) ? $user->email : '') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sp_id" class="col-md-3 control-label">Service Provider:</label>
                        <div class="col-md-4">
                            <select class="form-control" id="sp_id" name="sp_id">

                                @if ( isset($roles) && isset(Auth::user()->roles()->first()->name) && Auth::user()->roles()->first()->name == 'Administrator' )
                                <option value="0">{{ ucfirst(config('app.name')) }} Admin</option>
                                @endif
                                @foreach($service_providers as $service_provider)
                                    <option value="{{ $service_provider['ServiceProviderId'] }}" {{ ( isset($user) && $service_provider['ServiceProviderId'] == $user->sp_id ? 'selected' : '') }}> {{ $service_provider['ServiceProviderName'] }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(isset($roles))
                    <div class="form-group">
                        <label for="ro_id" class="col-md-3 control-label">Role:</label>
                        <div class="col-md-4">
                            <select class="form-control" name="ro_id">
                                @foreach($roles as $rol)
                                    <option value="{{ $rol['id'] }}" {{ ( isset($user) && $user->roles()->first()->id == $rol['id'] ? 'selected' : '' ) }}> {{ $rol['name'] }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    @if(!isset($user))
                    <div class="form-group">
                        <label for="password" class="col-md-3 control-label">Password</label>

                        <div class="col-md-4">
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="col-md-3 control-label">Password Confirmation</label>

                        <div class="col-md-4">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    @endif

                    @if(isset($roles))
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="status">Active:</label>
                        <div class="col-md-4 margin-top-10">
                            <input type="checkbox" class="checkbox_all" id="status" name="status" {{ ( isset($user) && $user->status == 1 ? 'checked' : '' ) }}>
                        </div>
                    </div>
                    @endif

                </div>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Submit</button>
                        </div>
                    </div>
                </div>

            </form>

            @include ('orbit.errors')
        </div>
    </div>