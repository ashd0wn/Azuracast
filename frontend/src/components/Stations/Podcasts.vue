<template>
    <card-page>
        <template #header>
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2 class="card-title">
                        {{ $gettext('Podcasts') }}
                    </h2>
                </div>
                <div class="col-md-5 text-end">
                    <stations-common-quota
                        ref="$quota"
                        :quota-url="quotaUrl"
                    />
                </div>
            </div>
        </template>
        <template #actions>
            <add-button
                :text="$gettext('Add Podcast')"
                @click="doCreate"
            />
        </template>

        <data-table
            id="station_podcasts"
            ref="$datatable"
            paginated
            :fields="fields"
            :api-url="listUrl"
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
                        :href="item.links.public_episodes"
                        target="_blank"
                    >{{ $gettext('Public Page') }}</a> &bull;
                    <a
                        :href="item.links.public_feed"
                        target="_blank"
                    >{{ $gettext('RSS Feed') }}</a>
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
                    <router-link
                        class="btn btn-secondary"
                        :to="{name: 'stations:podcast:episodes', params: {podcast_id: item.id}}"
                    >
                        {{ $gettext('Episodes') }}
                    </router-link>
                </div>
            </template>
        </data-table>
    </card-page>

    <edit-modal
        ref="$editPodcastModal"
        :create-url="listUrl"
        :new-art-url="newArtUrl"
        :language-options="languageOptions"
        :categories-options="categoriesOptions"
        @relist="relist"
    />
</template>

<script setup lang="ts">
import DataTable, {DataTableField} from '~/components/Common/DataTable.vue';
import EditModal from './Podcasts/PodcastEditModal.vue';
import AlbumArt from '~/components/Common/AlbumArt.vue';
import StationsCommonQuota from "~/components/Stations/Common/Quota.vue";
import {useTranslate} from "~/vendor/gettext";
import {ref} from "vue";
import {getStationApiUrl} from "~/router";
import AddButton from "~/components/Common/AddButton.vue";
import useHasDatatable, {DataTableTemplateRef} from "~/functions/useHasDatatable.ts";
import CardPage from "~/components/Common/CardPage.vue";
import useConfirmAndDelete from "~/functions/useConfirmAndDelete.ts";
import useHasEditModal from "~/functions/useHasEditModal.ts";

const props = defineProps({
    languageOptions: {
        type: Object,
        required: true
    },
    categoriesOptions: {
        type: Object,
        required: true
    },
});

const quotaUrl = getStationApiUrl('/quota/station_podcasts');
const listUrl = getStationApiUrl('/podcasts');
const newArtUrl = getStationApiUrl('/podcasts/art');

const {$gettext} = useTranslate();

const fields: DataTableField[] = [
    {key: 'art', label: $gettext('Art'), sortable: false, class: 'shrink pe-0'},
    {key: 'title', label: $gettext('Podcast'), sortable: false},
    {
        key: 'episodes',
        label: $gettext('# Episodes'),
        sortable: false,
    },
    {key: 'actions', label: $gettext('Actions'), sortable: false, class: 'shrink'}
];

const $quota = ref<InstanceType<typeof StationsCommonQuota> | null>(null);

const $datatable = ref<DataTableTemplateRef>(null);
const {refresh} = useHasDatatable($datatable);

const relist = () => {
    $quota.value?.update();
    refresh();
};

const $editPodcastModal = ref<InstanceType<typeof EditModal> | null>(null);
const {doCreate, doEdit} = useHasEditModal($editPodcastModal);

const {doDelete} = useConfirmAndDelete(
    $gettext('Delete Podcast?'),
    () => relist()
);
</script>
