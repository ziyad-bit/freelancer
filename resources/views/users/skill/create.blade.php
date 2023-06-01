@extends('layouts.app')

@section('content')
    <input type="hidden" value="{{ route('skill.store') }}" id="store_route">

    <div class="alert alert-success text-center success_msg" style="display:none"></div>

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <input type="hidden" value="{{ $skills }}" id="skills_input">

    <form id="skill_form">
        <div class="card text-white bg-dark mb-3" style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">add skills
                <button type="button" class="btn btn-primary add_button" style="margin-left: 314px;">
                    add more skill
                </button>
            </div>

            <div class="card-body body">

                <div class="form-group skills">
                    <label for="exampleInputEmail1">
                        1- skill
                    </label>
                    <input list="skills" name="skills_name[1]" class="form-control input">

                    <datalist id="skills">
                        @forelse ($skills as $skill)
                            <option value="{{ $skill->skill }}">
                            @empty
                                -
                        @endforelse
                    </datalist>

                    <small style="color: red" class="errors" id="skills_name.1_err">

                    </small>

                </div>


            </div>
        </div>
        <button type="button" class="btn btn-primary submit_btn" style="margin-top: 10px;margin-bottom: 10px"> submit
        </button>
    </form>
@endsection

@section('script')
    <script defer src="{{ asset('js/general.js') }}"></script>
    <script defer src="{{ asset('js/skill/create.js') }}"></script>
    <script defer src="{{ asset('js/skill/add.js') }}"></script>
@endsection
