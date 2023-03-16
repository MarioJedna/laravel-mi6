import { useEffect, useState } from "react";
import axios from "axios";
import MissionEditForm from "./MissionEditForm";
import { Link, redirect } from "react-router-dom";

export default function Missions() {
    const [missions, setMissions] = useState([]);
    const [missionId, setMissionId] = useState(null);

    const loadMissions = async () => {
        try {
            let response = await axios.get("/api/missions");
            setMissions(response.data);
        } catch (error) {
            console.log(error);
        }
    };

    useEffect(() => {
        loadMissions();
    }, []);

    const sendEmail = async (missinoId) => {
        try {
            let response = await axios.get(`api/send-mission/${missinoId}`);
            console.log(response.data);
        } catch (error) {
            console.log(error);
        }
    };

    return (
        <div className="missions-container">
            {missionId ? (
                <MissionEditForm
                    missionId={missionId}
                    setMissionId={setMissionId}
                />
            ) : (
                missions.map((mission) => {
                    return (
                        <div
                            key={mission.id}
                            className="missions-container__mission"
                        >
                            <p>Name: {mission.name}</p>
                            <p>Year: {mission.year}</p>
                            <p>
                                Outcome:{" "}
                                {mission.outcome !== null
                                    ? mission.outcome == 1
                                        ? "Success"
                                        : "Failure"
                                    : "Unknown"}
                            </p>
                            <Link to={`/missions/${mission.id}/edit`}>
                                EDIT
                            </Link>
                            <br />
                            <button
                                onClick={() => {
                                    sendEmail(mission.id);
                                }}
                            >
                                Send Email with details
                            </button>
                            <hr />
                        </div>
                    );
                })
            )}
        </div>
    );
}
