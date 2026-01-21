import {useFolderStore} from '@/stores/folders.ts';
import {useRoute, useRouter} from 'vue-router';

export default function handleOpenFile() {
    const route       = useRoute();
    const router      = useRouter();
    const folderStore = useFolderStore();

    if (route.path !== '/') {
        return;
    }
    for (const folder of folderStore.folders) {
        for (const file of folder.files) {
            if (file.open) {
                router.push('/log?file=' + encodeURI(file.identifier));
                break;
            }
        }
    }
}
