    @if(($response))
    <div class="box box-primary">
        <div class="box-body">
            <table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Task Name</th>
                        <th>Planned Time(hrs.)</th>
                        <th>Log Time(hrs.)</th>
                    </tr>
                </thead>
               
              <tbody id="">
                  @foreach($response as $detail)
                    @foreach($detail['get_project'] as $project)
                        @foreach($project['list'] as $taskList)
                            @foreach($taskList['details'] as $taskDetail)
                                @foreach($taskDetail['logs'] as $time_log)
                                    <tr>
                                        <td>{{$project['project_name']}}</td>
                                        <td>{{$taskDetail['title']}}</td>
                                        <td>{{$taskDetail['work']}}</td>
                                        <td>{{convertToHoursMins($time_log['log_time'])}}</td>                          
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                  @endforeach
                </tbody>
            </table>
        </div>    
    @else
        <div class="box-footer clearfix">
            NO RECORDS FOUND
        </div>
    </div>    
    @endif    
