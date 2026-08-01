package app.keeplore;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;

import com.getcapacitor.BridgeActivity;

/**
 * Loads the tapped https://keeplore.app/... URL into the WebView when
 * the app is opened via an App Link (e.g. a link in the "interact by" email).
 *
 * Capacitor points the WebView at the remote site (server.url) but does not
 * navigate it to the deep-linked path on its own — onNewIntent only notifies
 * plugins — so we do it here for both cold start and warm resume.
 */
public class MainActivity extends BridgeActivity {

    private static final String APP_HOST = "keeplore.app";

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Cold start: app launched by tapping an App Link.
        navigateToAppLink(getIntent());
    }

    @Override
    public void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        // Warm resume: app already running (launchMode singleTask).
        setIntent(intent);
        navigateToAppLink(intent);
    }

    private void navigateToAppLink(Intent intent) {
        if (intent == null || !Intent.ACTION_VIEW.equals(intent.getAction())) {
            return;
        }
        Uri data = intent.getData();
        if (data == null || !APP_HOST.equalsIgnoreCase(data.getHost())) {
            return;
        }
        final String url = data.toString();
        if (getBridge() == null || getBridge().getWebView() == null) {
            return;
        }
        getBridge().getWebView().post(() -> getBridge().getWebView().loadUrl(url));
    }
}
