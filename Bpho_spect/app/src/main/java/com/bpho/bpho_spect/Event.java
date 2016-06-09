package com.bpho.bpho_spect;

/**
 * Created by Thomas on 12-04-16.
 */
import android.os.Parcelable;

import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Date;

public class Event implements Serializable{
    private String id;
    private String title, thumbnailUrl;
    private String date;
    private String description;
    private String startDate, endDate, startTime, endTime;
    private String city, cityCode, address, addressInfos, fullAddress;
    private String price;
    private String reservationLink;

    public Event(JSONObject obj) {
        try {
            setId(obj.getString("id"));
            setTitle(obj.getString("title"));
            setDescription(obj.getString("description"));
            setThumbnailUrl("http://91.121.151.137/TFE/images/e" + obj.getString("id") + ".jpg");
            setStartDate(obj.getString("startDate"));
            setEndDate(obj.getString("endDate"));
            setDate(getStartDate(), getEndDate());
            setTime(getStartDate(), getEndDate());
            setCity(obj.getString("city"));
            setCityCode(obj.getString("cityCode"));
            setAddress(obj.getString("address"));
            setAddressInfos(obj.getString("addressInfos"));
            setFullAddress(getAddressInfos(), getAddress(), getCity(), getCityCode());
            setPrice(obj.getString("price"));
            setReservationLink(obj.getString("reservation"));
        } catch (Exception e) {

        }
    }

    public Event(String name, String thumbnailUrl, String date, String address) {
        this.title = name;
        this.thumbnailUrl = thumbnailUrl;
        this.date = date;
        this.address = address;
    }

    public String getId() {
        return this.id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTitle() {
        return title;
    }
    public void setTitle(String name) {
        if(name.equals("null")) {
            this.title = "";
        } else {
            this.title = name;
        }
    }

    public String getThumbnailUrl() {
        return thumbnailUrl;
    }
    public void setThumbnailUrl(String thumbnailUrl) {
        this.thumbnailUrl = thumbnailUrl;
    }

    public String getDescription() {
        return this.description;
    }
    public void setDescription(String description) {
        if (description.equals("null")) {
            this.description = "";
        } else {
            this.description = description;
        }
    }

    public String getStartDate() {
        return this.startDate;
    }
    public void setStartDate(String startDate) {
        this.startDate = startDate;
    }

    public String getEndDate() {
        return this.endDate;
    }
    public void setEndDate(String endDate) {
        this.endDate = endDate;
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
            this.date = startDate + " au " + endDate;
        else
            this.date = startDate;
    }

    public String getStartTime() {
        return startTime;
    }
    public String getEndTime() {
        return endTime;
    }
    public void setTime(String startDate, String endDate) {
        String[] temp = startDate.split(" ");
        String[] startTimeTemp = temp[1].split(":");
        this.startTime = startTimeTemp[0] + ":" + startTimeTemp[1];
        String[] temp2 = endDate.split(" ");
        String[] endTimeTemp = temp[1].split(":");
        this.endTime = endTimeTemp[0] + ":" + endTimeTemp[1];
    }

    public String getCity() {
        return this.city;
    }
    public void setCity(String city) {
        this.city = city;
    }

    public String getCityCode() {
        return this.cityCode;
    }
    public void setCityCode(String cityCode) {
        this.cityCode = cityCode;
    }

    public String getAddress() {
        return  this.address;
    }
    public void setAddress(String address) {
        this.address = address;
    }

    public String getAddressInfos() {
        return this.addressInfos;
    }
    public void setAddressInfos(String addressInfos) {
        this.addressInfos = addressInfos;
    }

    public String getFullAddress() {
        return fullAddress;
    }
    public void setFullAddress(String addressInfos, String address, String city, String cityCode) {
        String addressTemp = "";
        if (!addressInfos.equals("null") && !addressInfos.equals("")) {
            addressTemp = addressTemp + addressInfos;
        }
        if (!address.equals("null") && !address.equals("")) {
            if(!addressTemp.equals("")) {
                addressTemp = addressTemp + " - ";
            }
            addressTemp = addressTemp + address;
        }
        if (!cityCode.equals("null") && !cityCode.equals("") && !cityCode.equals("0")) {
            if(!addressTemp.equals("")) {
                addressTemp = addressTemp + " - ";
            }
            addressTemp = addressTemp + cityCode;
        }
        if (!city.equals("null") && !city.equals("")) {
            if(!addressTemp.equals("")) {
                addressTemp = addressTemp + " - ";
            }
            addressTemp = addressTemp + city;
        }
        this.fullAddress = addressTemp;
    }

    public String getPrice() {
        return this.price;
    }
    public void setPrice(String price) {
        this.price = price;
    }

    public String getReservationLink() {
        return this.reservationLink;
    }
    public void setReservationLink(String reservationLink) {
        this.reservationLink = reservationLink;
    }
}