<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <div id="sidebar-menu">
            <ul class="list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                {{--                <li>--}}
                {{--                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">--}}
                {{--                        <i class="fa-solid fa-chart-simple"></i>--}}
                {{--                        <span>Dashboard</span>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                <li>
                    <a href="{{route("admin.admins.list")}}" class="waves-effect">
                        <i class="fa-regular fa-user"></i>
                        <span>Admin Users</span>
                    </a>
                </li>


                <li>
                    <a href="{{route("admin.survey_users.list")}}" class="waves-effect">
                        <i class="fa-regular fa-user"></i>
                        <span>Survey Users</span>
                    </a>
                </li>

                <li>
                    <a href="{{route("admin.surveys.list")}}" class="waves-effect">
                        <i class="fa-regular fa-user"></i>
                        <span>Surveys</span>
                    </a>
                </li>

                <li>
                    <a href="{{route("admin.companies.list")}}" class="waves-effect">
                        <i class="fa-regular fa-building"></i>
                        <span>Companies</span>
                    </a>
                </li>

                <li>
                    <a href="{{route("admin.advertisement.list")}}" class="waves-effect">
                        <i class="fa-regular fa-dollar"></i>
                        <span>Advertisement</span>
                    </a>
                </li>

                <li>
                    <a href="{{route("admin.video.list")}}" class="waves-effect">
                        <i class="fa-regular fa-video-camera"></i>
                        <span>Videos</span>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</div>
