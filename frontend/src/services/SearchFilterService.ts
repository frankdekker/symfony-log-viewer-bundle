export default class SearchFilterService {

    public createFilter(fields: HTMLInputElement[], strip: string | undefined, pattern: string) {
        let replaced = false;

        for (const input of fields) {
            const key = input.name;
            let val   = input.value.trim();
            if (strip !== undefined) {
                val = val.replace(strip, '');
            }

            const escapeVal = (val.indexOf(' ') === -1 ? val : '"' + val + '"');
            const matches   = pattern.match('\\{' + key + '(=)?\\}');
            if (matches !== null) {
                pattern  = pattern.replace(matches[0], val === '' ? '' : escapeVal + (matches[1] ?? ''));
                replaced = replaced || val !== '';
            }
            input.value = '';
        }

        return [pattern, replaced];
    }
}
