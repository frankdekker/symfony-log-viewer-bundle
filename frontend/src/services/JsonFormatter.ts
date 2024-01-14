export function isEmptyJson(data: { [key: string]: unknown } | string): boolean {
  if (typeof data === 'string') {
    return data === '' || data === '{}' || data === '[]'
  }
  return Object.keys(data).length === 0
}

export function prettyFormatJson(data: { [key: string]: unknown } | string): string {
  let json = data
  if (typeof data === 'string') {
    try {
      json = JSON.parse(data)
    } catch (_) {
      return data
    }
  }

  return json.length === 0 ? '' : JSON.stringify(json, null, 2)
}
