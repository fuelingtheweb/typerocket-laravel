@extends('layouts.app')

@section('content')

    @if (!empty($errors) && count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header">
        <h3>Edit Media</h3>
    </div>

    <div class="typerocket-container">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ $form->getModel()->sizes['s3']['full'] }}?w=120&h=120" alt="{{$form->getModel()->alt}}">
            </div>
            <div class="col-md-10">
                {!! $form->open() !!}
                {!! $form->text('alt')->setLabel('SEO Image Description') !!}
                {!! $form->text('caption')->setLabel('Caption')->setSetting('help', 'Used by search feature') !!}
                {!! $form->submit('Update Media') !!}
                {!! $form->close() !!}
            </div>
        </div>

    </div>


@stop
