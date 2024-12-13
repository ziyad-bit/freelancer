@extends('layouts.app')

@section('header')
    <script defer src="{{ asset('js/skill/create.js')}}?v={{ filemtime(public_path('js/skill/create.js')) }}"></script>
    <script defer src="{{ asset('js/skill/add.js')}}?v={{ filemtime(public_path('js/skill/add.js')) }}"></script>
@endsection

@section('content')
    <input type="hidden" value="{{ route('skill.store') }}" id="store_route">

    <div class="alert alert-success text-center success_msg" style="display:none"></div>

    @if (Session::has('error'))
        <div class="alert alert-danger text-center">{{ Session::get('error') }}</div>
    @endif

    <input type="hidden" value="{{ $skills }}" id="skills_input">

    <form id="skill_form">
        <div class="card card_form text-white bg-dark mb-3" style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">add skills
                <button type="button" class="btn btn-primary add_button" style="margin-left: 314px;">
                    add more skill
                </button>
            </div>
                        
            <input type="hidden" name="num_input" id="num_input" value="{{old('num_input')}}">

            <div class="card-body body">

                <div class="form-group skills">
                    <label for="exampleInputEmail1">
                        - skill
                    </label>
                    <input list="skills" id="1" name="skills_name[1]" class="form-control input">

                    <datalist id="skills">
                        @forelse ($skills as $skill)
                            <option value="{{ $skill->skill }}" data-value='{{$skill->id}}'>
                            @empty
                                -
                        @endforelse
                    </datalist>

                    <small style="color: red" class="errors" id="skills_name.1_err">

                    </small>

                </div>
                <input type="hidden" name="skills_id[1]" id="skill_id_1">

            </div>
        </div>
        <button type="submit" class="btn btn-primary submit_btn" style="margin-top: 10px;margin-bottom: 10px"> submit
        </button>
    </form>
@endsection
