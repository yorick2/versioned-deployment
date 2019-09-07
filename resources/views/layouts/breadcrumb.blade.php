@auth
<div class="container">
    <ol class="breadcrumb bg-transparent pl-0 pr-0 mb-0">
        <?php $segments = ''; ?>
        <?php $i=0; ?>
        <?php $requestSegments = Request::segments(); ?>
        <?php if(count($requestSegments) > 1) : ?>
            <?php foreach($requestSegments as $key => $segment): ?>
                <?php $segments .= '/'.$segment; ?>
                <li>
                    <?php if($i!=0): ?>&nbsp;&gt;&nbsp;<?php endif; ?>
                    <?php if(($key==1) && isset($project)): ?>
                        <a class="text-dark" href="{{ url($segments) }}">{{$project->name}}</a>
                    <?php elseif(($key==3) && isset($server)): ?>
                        <a class="text-dark" href="{{ url($segments) }}">{{$server->name}}</a>
                    <?php else: ?>
                        <a class="text-dark" href="{{ url($segments) }}">{{$segment}}</a>
                    <?php endif; ?>
                </li>
                <?php $i++; ?>
            <?php endforeach ?>
        <?php endif; ?>
    </ol>
</div>
@endauth
