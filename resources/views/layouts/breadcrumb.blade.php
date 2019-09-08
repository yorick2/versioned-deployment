@auth
<nav class="container text-capitalize" aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent pl-0 pr-0 mb-0">
        <?php $segments = ''; ?>
        <?php $i=1; ?>
        <?php $requestSegments = Request::segments(); ?>
        <?php $count = count($requestSegments); ?>
        <?php if($count > 1) : ?>
            <?php foreach($requestSegments as $key => $segment): ?>
                <?php $segments .= '/'.$segment; ?>
                <?php if($count == $i): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php if(($key==1) && isset($project)): ?>
                            {{$project->name}}
                        <?php elseif(($key==3) && isset($server)): ?>
                            {{$server->name}}
                        <?php else: ?>
                            {{$segment}}
                        <?php endif; ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item" >
                        <a class="" href="{{url($segments)}}">
                            <?php if(($key==1) && isset($project)): ?>
                                {{$project->name}}
                            <?php elseif(($key==3) && isset($server)): ?>
                                {{$server->name}}
                            <?php else: ?>
                                {{$segment}}
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif;?>
                <?php $i++; ?>
            <?php endforeach ?>
        <?php endif; ?>
    </ol>
</nav>
@endauth
