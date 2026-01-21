import {useRouter} from 'vue-router';

export default function afterRouteOnce(guard: () => void) {
    let called = false;
    useRouter().afterEach(() => {
        if (called) {
            return;
        }
        called = true;
        guard();
    });
}
