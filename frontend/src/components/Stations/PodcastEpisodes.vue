<template>
    <section
        class="card"
        role="region"
    >
        <div class="card-header text-bg-primary">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 pe-3">
                            <album-art :src="podcast.art" />
                        </div>
                        <div class="flex-fill">
                            <h2 class="card-title">
                                {{ podcast.title }}
                            </h2>
                            <h4 class="card-subtitle">
                                {{ $gettext('Episodes') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-end">
                    <stations-common-quota
                        ref="$quota"
                        :quota-url="quotaUrl"
                    />
                </div>
            </div>
        </div>

        <div class="card-body buttons">
            <router-link
                class="btn btn-secondary"
                :to="{name: 'stations:podcasts:index'}"
            >
                <icon :icon="IconChevronLeft" />
                {{ $gettext('All Podcasts') }}
            </router-link>

            <add-button
                :text="$gettext('Add Episode')"
                @click="doCreate"
            />
        </div>

        <data-table
            id="station_podcast_episodes"
            ref="$datatable"
            paginated
            :fields="fields"
            :api-url="podcast.links.episodes"
        >
            <template #cell(art)="{item}">
                <album-art :src="item.art" />
            </template>
            <template #cell(title)="{item}">
                <h5 class="m-0">
                    {{ item.title }}
                </h5>
                <div v-if="item.is_published">
                    <a
                        :href="item.links.public"
                        target="_blank"
                    >{{ $gettext('Public Page') }}</a>
                </div>
                <div
                    v-else
                    class="badges"
                >
                    <span class="badge text-bg-info">
                        {{ $gettext('Unpublished') }}
                    </span>
                </div>
            </template>
            <template #cell(podcast_media)="{item}">
                <template v-if="item.media">
                    <span>{{ item.media.original_name }}</span>
                    <br>
                    <small>{{ item.media.length_text }}</small>
                </template>
            </template>
            <template #cell(explicit)="{item}">
                <span
                    v-if="item.explicit"
                    class="badge text-bg-danger"
                >{{ $gettext('Explicit') }}</span>
                <span v-else>&nbsp;</span>
            </template>
            <template #cell(actions)="{item}">
                <div class="btn-group btn-group-sm">
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="doEdit(item.links.self)"
                    >
                        {{ $gettext('Edit') }}
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        @click="doDelete(item.links.self)"
                    >
                        {{ $gettext('Delete') }}
                    </button>
                </div>
            </template>
        </data-table>
    </section>

    <edit-modal
        ref="$editEpisodeModal"
        :create-url="podcast.links.episodes"
        :new-art-url="podcast.links.episode_new_art"
        :new-media-url="podcast.links.episode_new_media"
        :podcast-id="podcast.id"
        @relist="relist"
    />
</template>

<script setup lang="ts">
import DataTable, {DataTableField} from '~/components/Common/DataTable.vue';
import EditModal from './Podcasts/EpisodeEditModal.vue';
import Icon from '~/components/Common/Icon.vue';
import AlbumArt from '~/components/Common/AlbumArt.vue';
import StationsCommonQuota from "~/components/Stations/Common/Quota.vue";
import {useTranslate} from "~/vendor/gettext";
import {ref} from "vue";
import AddButton from "~/components/Common/AddButton.vue";
import {IconChevronLeft} from "~/components/Common/icons";
import useHasDatatable, {DataTableTemplateRef} from "~/functions/useHasDatatable.ts";
import {getStationApiUrl} from "~/router.ts";
import useConfirmAndDelete from "~/functions/useConfirmAndDelete.ts";
import {ApiPodcast} from "~/entities/ApiInterfaces.ts";
import useHasEditModal from "~/functions/useHasEditModal.ts";

const props = defineProps<{
    podcast: ApiPodcast
}>();

const quotaUrl = getStationApiUrl('/quota/station_podcasts');

const {$gettext} = useTranslate();

const fields: DataTableField[] = [
    {key: 'art', label: $gettext('Art'), sortable: false, class: 'shrink pe-0'},
    {key: 'title', label: $gettext('Episode'), sortable: false},
    {key: 'podcast_media', label: $gettext('File Name'), sortable: false},
    {key: 'explicit', label: $gettext('Explicit'), sortable: false},
    {key: 'actions', label: $gettext('Actions'), sortable: false, class: 'shrink'}
];

const $quota = ref<InstanceType<typeof StationsCommonQuota> | null>(null);

const $datatable = ref<DataTableTemplateRef>(null);
const {refresh} = useHasDatatable($datatable);

const relist = () => {
    $quota.value?.update();
    refresh();
};

const $editEpisodeModal = ref<InstanceType<typeof EditModal> | null>(null);
const {doCreate, doEdit} = useHasEditModal($editEpisodeModal);

const {doDelete} = useConfirmAndDelete(
    $gettext('Delete Episode?'),
    () => relist()
);
</script>
