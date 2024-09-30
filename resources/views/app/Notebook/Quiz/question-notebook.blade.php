@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <style>
        .form-check-question {
            font-size: 18px !important;
        }

        .form-check-label {
            font-size: 18px !important;
        }

        .question {
            font-size: 18px !important;
        }
    </style>

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-3">
        @if($unansweredQuestions && $unansweredQuestions->count() > 0)
            @foreach($unansweredQuestions as $index => $notebookQuestion)
                @php
                    $question = $notebookQuestion->question;
                @endphp

                @if($question)
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                <h6 class="question">
                                    Questão: {{ $nextQuestionNumber }} de {{ $totalQuestions }}
                                </h6>
                                <small><b>Conteúdo/Tópico:</b></small> <br>
                                <small><b>{{ $question->responsesCount(Auth::user()->id) }}</b> Resolvidas</small> <small class="text-success"><b>{{ $question->correctCount(Auth::user()->id) }}</b> Acertos</small> <small class="text-danger"><b>{{ $question->wrogCount(Auth::user()->id) }}</b> Erros</small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="btn-group">
                                    <a class="btn btn-outline-dark" title="Dados da Questão"><i class="bi bi-pie-chart"></i></a>
                                    <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#commentModal" title="Comentário do Professor"><i class="bi bi-chat-text"></i></button>
                                </div>

                                <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Comentário do Professor</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {!! $question->comment_text !!}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <h6 class="card-title p-2 mt-2 mb-3 bg-light"> <a href="">#{{ $question->id }}</a> {{ $question->question_text }} </h6>
                        <form id="questionForm" method="POST" action="{{ route('submitAnswerAndNext', [$notebook->id, $notebookQuestion->id, $unansweredQuestions->currentPage()]) }}">
                            @csrf
                            
                            <input type="hidden" name="notebook_question_id" value="{{ $notebookQuestion->id }}">

                            <div class="bg-light p-3">
                                @foreach($question->options as $option)
                                    <div class="form-check-questio form-question option-container mb-3 p-2">
                                        <input class="form-check-input" type="radio" name="option_id" value="{{ $option->id }}" id="option{{ $option->id }}" onclick="selectOption(this)">
                                        <label class="form-check-label" for="option{{ $option->id }}"> {{ $option->option_text }} </label>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="mt-5">
                            <div class="text-center">
                                <div class="btn-group mt-3" role="group" style="width: 70%;">
                                    <a href="{{ route('caderno', ['id' => $notebook->id]) }}" class="btn btn-dark">SAIR</a>
                                    <a href="{{ route('delete-question-answer', ['notebook' => $notebook->id, 'question' => $question->id]) }}" title="Eliminar Questão" class="btn btn-outline-danger"><i class="bi bi-trash"></i></a>
                                    <button type="submit" class="btn btn-outline-success">RESPONDER</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <p>Questão não encontrada.</p>
                @endif
            @endforeach

            <div class="mt-3 text-center">
                {{ $unansweredQuestions->links() }}
            </div>
        @else
            <div class="text-center">
                <i class="bi bi-award text-success" style="font-size: 86px;"></i>
                <h6 class="card-title">Parabéns! <br> Você completou o caderno.</h6>
                <a href="{{ route('completing-notebook', ['id' => $notebook->id]) }}" class="btn btn-outline-success">Ver resultado</a>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('questionForm').addEventListener('submit', function(event) {
    
            event.preventDefault();
            const isOptionSelected = document.querySelector('input[name="option_id"]:checked');
            if (!isOptionSelected) {
                Swal.fire({
                    icon: 'info',
                    title: 'Selecione uma opção',
                    text: 'Por favor, escolha uma opção antes de enviar sua resposta.',
                    confirmButtonText: 'OK'
                });
            } else {
                this.submit();
            }
        });

        function selectOption(selected) {

            const optionContainers = document.querySelectorAll('.option-container');
            optionContainers.forEach(container => {
                container.classList.remove('selected-option');
            });

            const selectedContainer = selected.parentElement;
            selectedContainer.classList.add('selected-option');
        }
    </script>
@endsection