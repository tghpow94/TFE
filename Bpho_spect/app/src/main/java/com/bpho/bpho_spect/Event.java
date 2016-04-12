package com.bpho.bpho_spect;

/**
 * Created by Thomas on 12-04-16.
 */
import java.util.ArrayList;
import java.util.Date;

public class Event {
    private String title, thumbnailUrl;
    private String date;
    private String address;

    public Event() {
    }

    public Event(String name, String thumbnailUrl, String date, String address) {
        this.title = name;
        this.thumbnailUrl = thumbnailUrl;
        this.date = date;
        this.address = address;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String name) {
        this.title = name;
    }

    public String getThumbnailUrl() {
        return thumbnailUrl;
    }

    public void setThumbnailUrl(String thumbnailUrl) {
        this.thumbnailUrl = thumbnailUrl;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String startDate, String endDate) {
        String[] startTemp = startDate.split(" ");
        String[] start = startTemp[0].split("-");
        startDate = start[2] + "/" + start[1] + "/" + start[0];
        String[] endTemp = endDate.split(" ");
        String[] end = endTemp[0].split("-");
        endDate = end[2] + "/" + end[1] + "/" + end[0];
        if (!startDate.equals(endDate))
            this.date = "Du " + startDate + " au " + endDate;
        else
            this.date = "Le " + startDate;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String addressInfos, String address, String city) {
        this.address = addressInfos + " - " + city + " - " + address;
    }

}