export default async function apiCall(url, method = "GET", body = null, isDebug = false) {
    try {
        const options = { method, headers: {} };

        if (body) {
            if (body instanceof FormData) {
                // Don't set Content-Type; the browser will set it automatically
                options.body = body;
            } else {
                options.headers["Content-Type"] = "application/json";
                options.body = JSON.stringify(body);
            }
        }

        const response = await fetch(url, options);

        // If no content (204), return null
        if (response.status === 204) return null;

        const data = await response.json();

        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${data.error || response.statusText}`);
        }

        if (isDebug) console.log("API Response:", data);

        return data;
    } catch (error) {
        console.error("API Call Failed:", error.message);
        throw error;
    }
}
