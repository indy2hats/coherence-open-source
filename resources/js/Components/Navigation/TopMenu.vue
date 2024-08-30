<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { SiteSettings } from "@/types/siteSettingsTypes";

const page = usePage();
const siteSettings = computed(() => page.props.siteSettings as SiteSettings);
</script>

<template>
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="header-wrap row align-items-center position-relative">
            <div class="header-left col-sm-2">
                <a href="/dashboard" class="brand">
                    <img :src="siteSettings.company_logo" alt="logo" />
                </a>
            </div>
            <div class="header-right col-sm-10">
                <div class="search-wrap">
                    <div class="g-search-item pull-right">
                        <div class="inner-addon right-addon">
                            <i class="ri-search-line epms-icon epms-icon--1x">
                            </i>
                            <i class="fa fa-spinner fa-lg" style="display: none">
                            </i>
                            <input autocomplete="off" placeholder="Search projects, tasks, jira ids, comments.."
                                type="text" name="q" id="gSearch" class="form-control global-search-box" />
                        </div>
                    </div>
                    <div id="globalSearchResultWrapper" class="global-search"></div>
                </div>
                <ul class="navbar-controls">
                    <!-- TODO::check the condition -->
                    <li class="eod_report" id="eod_report_li">
                        <a href="#" data-toggle="modal" data-target="#add_eod_report" id="eodReportLink"
                            style="width: auto">
                            <i class="ri-add-line"></i> Add EOD Report
                        </a>
                    </li>

                    <li>
                        <a href="showProfile">
                            <i class="ri-user-line epms-icon epms-icon--1x"></i>
                        </a>
                    </li>

                    <li>
                        <a href="changePassword">
                            <i class="ri-settings-5-line epms-icon epms-icon--1x"></i>
                        </a>
                    </li>

                    <li>
                        <a href="logout">
                            <i class="ri-logout-circle-r-line epms-icon epms-icon--1x"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="ibox-content breadcrumbs-outer-div">
        <!-- TODO::render breadcrumb -->
        <!-- <button
            id="pause-task"
            class=" {{Session::has('taskRunning') ? '': 'hidden'  }}"
        >
            <i class="ri-pause-line"></i>
        </button> -->
        <!-- TODO::make this available -->
        <button id="pause-task" class="">
            <i class="ri-pause-line"></i>
        </button>
    </div>
    <!-- TODO::Add add_eod_report modal -->
</template>