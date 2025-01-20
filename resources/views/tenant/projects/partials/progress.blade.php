<div class="col-md-3 project-card-container">
    <div class="project-card project-progress-card text-blue">
        <div class="project-card-body">
            <h5 class="project-card-title">{{number_format($progress, 0 )}}%</h5>
            <p class="project-card-text">Progress</p>
            <div class="project-card-progress-container">
                <div class="project-card-progress-bar" style="width: {{$progress}}%;" role="progressbar" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>