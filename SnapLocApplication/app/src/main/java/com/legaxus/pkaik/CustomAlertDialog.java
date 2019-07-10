package com.legaxus.pkaik;

import android.app.Dialog;
import android.content.Context;
import android.os.Bundle;
import android.view.View;

public class CustomAlertDialog extends Dialog {
    int custom_layout_id;
    View view;

    public CustomAlertDialog(Context context, int custom_layout_id) {
        super(context);
        this.custom_layout_id = custom_layout_id;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(custom_layout_id);

        view = findViewById(android.R.id.content);
    }
}
