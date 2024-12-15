@extends('layouts.app')

@section('header')
    <script defer src="{{ asset('js/skill/create.js') }}?v={{ filemtime(public_path('js/skill/create.js')) }}"></script>
    <script defer src="{{ asset('js/skill/add.js') }}?v={{ filemtime(public_path('js/skill/add.js')) }}"></script>
@endsection

@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger text-center">
            {{ Session::get('error') }}
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success text-center">{{ Session::get('success') }}</div>
    @endif


    <input type="hidden" value="{{ $skills }}" id="skills_input">

    <form id="skill_form" action="{{ route('skill.store') }}" method="POST">
        @csrf
        <div class="card card_form text-white bg-success mb-3" style="max-width: 34rem;margin-top: 20px">
            <div class="card-header">add skills
                <button type="button" class="btn btn-primary add_button" style="margin-left: 314px;">
                    add more skill
                </button>
            </div>

            <div class="card-body body">

                <div class="form-group skills">
                    @if (old('num_input') > 1)
                        @for ($i = 1; $i < old('num_input') + 1; $i++)
                            <div id="input{{ $i }}">
                                <label for="exampleInputEmail1">
                                    - skill
                                </label>

                                @if ($i != 1)
                                    <button type="button" class="btn-close  delete_skill" id="{{ $i }}">
                                    </button>
                                @endif

                                <input list="skills" id="{{ $i }}" value='{{ old("skills.$i.name") }}'
                                    name="skills[{{ $i }}][name]" class="form-control input" autocomplete="off">

                                <input type="hidden" name="skills[{{ $i }}][id]"
                                    id="skill_id_{{ $i }}"
                                    value='{{ old("skills.$i.id") ? old("skills.$i.id") : '' }}'>

                                @error("skills.$i.id")
                                    <div style="color: red;font-size: small">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @error('skills')
                                    <small style="color: red">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        @endfor
                    @else
                        <div id="input1">
                            <label for="exampleInputEmail1">
                                - skill
                            </label>

                            <input list="skills" id="1" name="skills[1][name]" class="form-control input"
                                autocomplete="off" value='{{ old('skills.1.name') }}'>

                            <input type="hidden" name="skills[1][id]" id="skill_id_1">

                            @error('skills.1.id')
                                <small style="color: red">
                                    {{ $message }}
                                </small>
                            @enderror

                            @error('skills')
                                <small style="color: red">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    @endif

                </div>

                <input type="hidden" id="num_input" required max="20"
                    value="{{ old('num_input') ? old('num_input') : 1 }}" name="num_input">
            </div>
        </div>
        <button type="submit" class="btn btn-primary submit_btn" style="margin-top: 10px;margin-bottom: 10px"> submit
        </button>
    </form>

    <datalist id="skills">
        @forelse ($skills as $skill)
            <option data-value="{{ $skill->id }}">
                {{ $skill->skill }}
            </option>
        @empty
            -
        @endforelse
    </datalist>
@endsection
